<?php

namespace App\Models\Chain;

/**
 * Description of TenderChain
 */
abstract class AbstractHandler
{

    protected $parms = array();
    protected $error = array('error' => '', 'code' => 0);
    protected $data = [];
    /**
     * 定义方法 错误消息返回变量
     */
    public $errorMessage;

    /**
     * 持有后继的责任对象
     *
     * @var object
     */
    protected $successor;

    /**
     * 示意处理请求的方法，虽然这个示意方法是没有传入参素的
     * 但实际是可以传入参数的，根据具体需要来选择是否传递参数
     */
    abstract public function handleRequest();

    /**
     * 取值方法
     *
     * @return object
     */
    public function getSuccessor()
    {
        return $this->successor;
    }

    /**
     * 赋值方法，设置后继的责任对象
     *
     * @param $objsuccessor
     */
    public function setSuccessor($objsuccessor)
    {
        $this->successor = $objsuccessor;
    }

    /**
     * 取值方法
     *
     * @return array
     */
    public function getSuccessData()
    {
        return $this->data;
    }

    /**
     * 设定方法
     *
     * @param $data
     *
     * @return mixed
     */
    public function setSuccessData($data)
    {
        return $this->data = $data;
    }
}
