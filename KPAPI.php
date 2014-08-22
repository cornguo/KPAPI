<?php namespace CornGuo;

class KPAPI implements \Iterator {

    private $_token = NULL;
    private $_urls = array();
    private $_urlKeys = array();
    private $_level = 0;
    protected $endpoint = 'http://api.kptaipei.tw/v1';
    protected $data = NULL;
    protected $pos = 0;

    public function __construct($token = NULL, $endpoint = NULL, $objs = NULL) {
        $level = substr_count($endpoint, '/') - substr_count($this->endpoint, '/');
        if ($level > 0) {
            $this->_level = $level;
        }

        $this->_token = $token;
        if (NULL !== $endpoint) {
            $this->endpoint = $endpoint;
        }
        if (NULL === $objs) {
            $objs = array('category', 'albums', 'videos');
        }

        $this->data = $objs;

        foreach ($objs as $obj) {
            if (isset($obj->id)) {
                $this->_urls[$obj->id] = $this->_generateUrl($obj->id);
            } elseif (is_string($obj)) {
                $this->_urls[$obj] = $this->_generateUrl($obj);
            }
        }
        $this->_urlKeys = array_keys($this->_urls);
    }

    public function __get($key) {
        if ($this->_level >= 2) {
            return $this->getData($key);
        } elseif (isset($this->_urls[$key])) {
            return $this->_getDataObj($key);
        }
        return $this->getData();
    }

    public function getData($key = NULL) {
        if (NULL !== $key) {
            if (isset($this->data->{$key})) {
                return $this->data->{$key};
            }

            $pos = array_search($key, $this->_urlKeys);

            if (false !== $pos) {
                return $this->data[$pos];
            }
        }
        return $this->data;
    }

    private function _getDataObj($key) {
        $data = file_get_contents($this->_urls[$key]);
        if (false === $data) {
            return -1;
        } else {
            $dataObject = json_decode($data);
            if (true === $dataObject->isSuccess) {
                if (isset($dataObject->data)) {
                    return new KPAPI($this->_token, $this->endpoint . '/' . $key, $dataObject->data);
                } else {
                    return $data;
                }
            }
        }
        return NULL;
    }

    private function _generateUrl($key = NULL) {
        $url = $this->endpoint . '/' . $key;
        return $url . '?accessToken=' . $this->_token;
    }

    public function key() {
        return $this->_urlKeys[$this->pos];
    }

    public function current() {
        return $this->{$this->_urlKeys[$this->pos]};
    }

    public function rewind() {
        $this->pos = 0;
    }

    public function next() {
        ++$this->pos;
    }

    public function valid() {
        return isset($this->_urlKeys[$this->pos]);
    }

}

