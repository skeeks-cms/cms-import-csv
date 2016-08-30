<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 29.08.2016
 */
namespace skeeks\cms\importCsv;
use skeeks\cms\importCsv\handlers\CsvHandler;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * @property CsvHandler[] $handlers
 *
 * Class ImportCsvComponent
 *
 * @package skeeks\cms\importCsv
 */
class ImportCsvComponent extends Component
{
    public function init()
    {
        $this->handlers = ArrayHelper::merge([
            CsvHandler::className() =>
            [
                'class' => CsvHandler::className()
            ]
        ], $this->handlers);

        parent::init();
    }

    /**
     * @return array
     */
    public function getDataForSelect()
    {
        return ArrayHelper::map($this->handlers, 'id', 'name');
    }
    
    
    
    
    private $_handlers = [];

    /**
     * @param array $clients list of handlers
     */
    public function setHandlers(array $handlers)
    {
        $this->_handlers = $handlers;
    }

    /**
     * @return HandlerProvider[] list of handlers.
     */
    public function getHandlers()
    {
        $handlers = [];
        foreach ($this->_handlers as $id => $server) {
            $handlers[$id] = $this->getHandler($id);
        }

        return $handlers;
    }

    /**
     * @param string $id service id.
     * @return HandlerProvider auth client instance.
     * @throws InvalidParamException on non existing client request.
     */
    public function getHandler($id)
    {
        if (!array_key_exists($id, $this->_handlers)) {
            throw new InvalidParamException("Unknown auth client '{$id}'.");
        }
        if (!is_object($this->_handlers[$id])) {
            $this->_handlers[$id] = $this->createHandler($id, $this->_handlers[$id]);
        }

        return $this->_handlers[$id];
    }

    /**
     * Checks if client exists in the hub.
     * @param string $id client id.
     * @return boolean whether client exist.
     */
    public function hasHandler($id)
    {
        return array_key_exists($id, $this->_handlers);
    }

    /**
     * Creates auth client instance from its array configuration.
     * @param string $id auth client id.
     * @param array $config auth client instance configuration.
     * @return ClientInterface auth client instance.
     */
    protected function createHandler($id, $config)
    {
        $config['id'] = $id;

        return \Yii::createObject($config);
    }
}
