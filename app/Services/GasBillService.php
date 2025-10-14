<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class GasBillService
{
    protected string $baseUrl;
    protected string $token;

    public function __construct()
    {
        $this->baseUrl = env('Bill_API_URL');
        $this->token = env('Bill_API_TOKEN');
    }

    /**
     * استعلام قبض گاز از وب‌سرویس
     *
     * @param string $token
     * @param string $billId
     * @param string $participateCode
     * @return array
     * @throws \Exception
     */
    public function inquire(?string $billId, ?string $participateCode): array
    {
        $url = "{$this->baseUrl}/GasBillInquiry";

        if (empty($billId) && empty($participateCode)) {
            throw new \Exception("حداقل یکی از مقادیر شناسه قبض یا کد اشتراک باید وارد شود.");
        }

        $parameters = [];
        if (!empty($billId)) {
            $parameters['GasBillID'] = $billId;
        }
        if (!empty($participateCode)) {
            $parameters['ParticipateCode'] = $participateCode;
        }

        $payload = [
            "Identity" => [
                "Token" => $this->token,
            ],
            "Parameters" => $parameters,
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, $payload);

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
    }
}
