<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class GasBillService
{
    protected string $baseUrl;
    protected string $token;
    protected int $cacheMinutes;

    public function __construct()
    {
        $this->baseUrl = config('services.bill_api.url', env('BILL_API_URL'));
        $this->token = config('services.bill_api.token', env('BILL_API_TOKEN'));
        $this->cacheMinutes = config('services.bill_api.token', env('BILL_CACHE_MINUTES'));
    }

    /**
     * استعلام قبض گاز از وب‌سرویس
     *
     * @param string $billId شناسه قبض
     * @param string $participateCode شماره اشتراک
     * @return array {
     *     "success": bool,
     *     "message": string,
     *     "data": ?array
     * }
     *
     * @throws Exception
     */

    public function inquire(?string $billId): array
    {
        if (empty($billId)) {
            throw new \Exception("حداقل یکی از مقادیر شناسه قبض یا کد اشتراک باید وارد شود.");
        }

        $cacheKey = "gas_bill_{$billId}" ;

        return Cache::remember($cacheKey, now()->addMinutes($this->cacheMinutes), function () use ($billId) {
            $url = "{$this->baseUrl}/GasBillInquiry";

            $parameters = [];
            if (!empty($billId)) {
                $parameters['GasBillID'] = $billId;
            }

            $payload = [
                "Identity" => [
                    "Token" => $this->token,
                ],
                "Parameters" => $parameters,
            ];

            try {
                $response = Http::timeout(15)
                    ->acceptJson()
                    ->asJson()
                    ->post($url, $payload);

                if ($response->failed()) {
                    throw new \Exception("ارتباط با وب‌سرویس برقرار نشد. (HTTP {$response->status()})");
                }

                $data = $response->json();

                if (!isset($data['Status']['Code']) || $data['Status']['Code'] !== 'G00000') {
                    throw new \Exception($data['Status']['Description'] ?? 'خطا در پاسخ سرویس');
                }

                return $data;
            } catch (RequestException $e) {
                throw new \Exception("خطا در ارسال درخواست: " . $e->getMessage());
            } catch (\Throwable $e) {
                throw new \Exception("خطای داخلی: " . $e->getMessage());
            }
        });
    }

    /**
     * حذف کش قبض خاص (مثلاً بعد از بروزرسانی)
     *
     * @param string $billId
     * @return void
     */
    public function clearCache(?string $billId, ?string $participateCod): void
    {
        Cache::forget($billId ? "gas_bill_{$billId}" : "gas_bill_{$participateCod}");
    }
}
