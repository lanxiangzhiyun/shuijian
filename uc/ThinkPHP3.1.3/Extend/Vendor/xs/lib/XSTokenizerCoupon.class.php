<?php
class XSTokenizerCoupon implements XSTokenizer
{
    public function getTokens($value, XSDocument $doc = null)
    {
        $ret = array();
        if (!empty($value))
            $ret = explode('|', $value);
        return $ret;
    }
}
