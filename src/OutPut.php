<?php


namespace AndPHP\OutPut;


use App\Constant\Error;

trait OutPut
{
    /**
     * 默认响应头
     * @var array
     */
    public static $header = [];

    /**
     * 设置返回 Header
     * @param $key
     * @param $values
     * @return $this
     */
    public function setHeader($key, $values)
    {
        self::$header[$key] = $values;
        return $this;
    }

    /**
     * 响应成功
     * @param $data
     * @param string $message
     * @return $this
     */
    public function success($data, $message = 'success')
    {
        return $this->outJson($data, $message, Error::SUCCESS, 200);
    }

    /**
     * 响应失败
     * @param int|array $code
     * @param null $msg
     * @return $this
     */
    public function error($code = 9999, $msg = null)
    {

        $resultCode = is_numeric($code) ? $code : (Error::UNKNOWN_ERROR);
        $resultMsg = null;
        if (is_array($code)) {
            $resultCode = $code['code'] ?? $resultCode;
            $resultMsg = $code['msg'] ?? $msg;
        }

        $resultMsg = $msg ?? $resultMsg;

        if (!$resultMsg && is_numeric($resultCode)) {
            $resultMsg = (new Error())->getMessage($resultCode);
        }

        return $this->outJson([], $resultMsg, $resultCode, 200);
    }

    /**
     * 返回json数据
     * @param $data
     * @param $message
     * @param $code
     * @param $status
     * @return $this
     */
    public function outJson($data, $message, $code, $status)
    {
        $notCamelCase = config('output.no_camel_case') ?? false;
        if ((is_array($data) || is_object($data)) && !$notCamelCase) {
            $dataResult = self::camelCase($data);
        } else {
            $dataResult = $data ?? [];
        }
        $result = [
            'code' => $code,
            'msg'  => $message,
            'data' => $dataResult,
        ];
        $headerKey = base64_decode('WC1Qb3dlcmVkLUJ5');
        $headerValue = base64_decode('QW5kUEhQ');
        self::$header[$headerKey] = $headerValue;
        return response()->json($result, $status)->withHeaders(self::$header)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param $arr
     * @param bool $ucfirst
     * @return array|string
     */
    protected function camelCase($arr, $ucfirst = FALSE)
    {
        if ($arr === null) {
            return "";
        }
        if (!is_array($arr) && !is_object($arr)) {   //如果非数组原样返回
            return $arr;
        }
        $temp = [];
        if (is_object($arr) && count((array)$arr) > 0) {
            $arr = (array)$arr;
        }
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                $key1 = self::toCamelCase($key, FALSE);
                $value1 = self::camelCase($value);
                $temp[$key1] = $value1;
            }
        }
        return $temp;
    }

    /**
     * @param $str
     * @param bool $ucfirst
     * @return mixed|string
     */
    protected function toCamelCase($str, $ucfirst = true)
    {
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ', '', lcfirst($str));
        return $ucfirst ? ucfirst($str) : $str;
    }
}
