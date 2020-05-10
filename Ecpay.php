<?php

namespace fall1600\Package\Ecpay;

use fall1600\Package\Ecpay\Info\Info;

class Ecpay
{
    /**
     * 付款-測試環境
     * @var string
     */
    public const CHECKOUT_URL_TEST = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';

    /**
     * 付款-正式環境
     * @var string
     */
    public const CHECKOUT_URL_PRODUCTION = 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5';

    /**
     * 查詢付款資訊-測試環境
     * @var string
     */
    public const QUERY_URL_TEST = 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V5';

    /**
     * 查詢付款資訊-正式環境
     * @var string
     */
    public const QUERY_URL_PRODUCTION = 'https://payment.ecpay.com.tw/Cashier/QueryTradeInfo/V5';

    /**
     * 決定URL 要使用正式或測試機
     * @var bool
     */
    protected $isProduction = true;

    /** @var string */
    protected $formId = 'ecpay-form';

    /**
     * @var Merchant
     */
    protected $merchant;

    public function checkout(Info $info)
    {
        echo <<<EOT
        <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="utf-8">
                </head>
                <body>
                    {$this->generateForm($info)}
                    <script>
                        var form = document.getElementById("$this->formId");
                        form.submit();
                    </script>
                </bod>
            </html>
        EOT;
    }

    public function query()
    {
    }

    public function generateForm(Info $info)
    {
        $url = $this->isProduction? static::CHECKOUT_URL_PRODUCTION: static::CHECKOUT_URL_TEST;

        $checksum = $this->merchant->countCheckSum($info);

        $form = "<form name='ecpay' id='$this->formId' method='post' action='$url' style='display: none'>";
        $form .= "<input type='hidden' name='CheckMacValue' value='$checksum' />";

        foreach ($info->getInfo() as $key => $value) {
            $form .=  "<input type='hidden' name='$key' value='$value' />";
        }

        $form .= "</form>";

        return $form;
    }

    /**
     * @param Merchant $merchant
     * @return $this
     */
    public function setMerchant(Merchant $merchant)
    {
        $this->merchant = $merchant;

        return $this;
    }

    /**
     * @param bool $isProduction
     * @return $this
     */
    public function setIsProduction(bool $isProduction)
    {
        $this->isProduction = $isProduction;

        return $this;
    }
}
