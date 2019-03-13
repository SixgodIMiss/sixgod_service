<?php
/**
 * 按道理讲是用A的公钥加密 A的私钥解密的
 */

namespace frame\extend;


class My_RSA
{
    private $method = 'RC4-40'; // 对称加密算法
    private $key = 'sixgod';    // 对称加密密钥

    private $public_key = '';   // 公钥路径
    private $private_key = '';

    public function __construct()
    {
        
    }
    
    public function set($name, $value)
    {
        if (!property_exists($this, $name))
        {
            throw new \Exception('RSA属性设置错误');
        }

        $this->$name = $value ? $value : '';
    }

    public function get($name)
    {
        if (!property_exists($this, $name))
        {
            throw new \Exception('RSA属性获取错误');
        }

        return $this->$name;
    }

    /**
     * 对称加解密
     * @param string 原文 || 密文
     * @param string 密钥
     * @param string 加密方法 （openssl_get_cipher_methods()）
     */
    public function crypt($to = 'en', $data = '', $key = '', $method = '')
    {
        $key = $key ? $key : $this->key;
        $method = $method ? $method : $this->method;

        return $to == 'en' ? openssl_encrypt($data, $method, $key) : openssl_decrypt($data, $method, $key);
    }

    /**
     * 公钥加密
     * @param string $origin_text 原文
     * @param string 
     */
    public function publicEncrypt($origin_text = '')
    {
        // 查验公钥
        $public_key = openssl_pkey_get_public(file_get_contents($this->public_key));
        
        // 获取公钥加密密文
        openssl_public_encrypt($origin_text, $cipher_text, $public_key);

        $cipher_text = base64_encode($cipher_text);
        
        return $cipher_text;
    }

    /**
     * 私钥解密
     * @param string $cipher_text 公钥加密密文
     * @return string $origin_text 原文
     */
    public function privateDecrypt($cipher_text = '')
    {
        // 查验私钥
        $private_key = openssl_pkey_get_private(file_get_contents($this->private_key));

        $cipher_text = base64_decode($cipher_text);

        // 解密密文
        openssl_private_decrypt($cipher_text, $origin_text, $private_key);

        return $origin_text;
    }
}

// $rsa = new My_RSA();
// $rsa->set('public_key', __DIR__ .'/rsa_public_key.pem');
// $rsa->set('private_key', __DIR__ .'/rsa_private_key.pem');
// var_dump($rsa->privateDecrypt($rsa->publicEncrypt('1/\/23_:123')));