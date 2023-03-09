<?php
abstract class CronosBaseManagerController extends modExtraManagerController {
    /** @var \Cronos\Cronos $cronos */
    public $cronos;

    public function initialize(): void
    {
        $this->cronos = $this->modx->services->get('cronos');

        $this->addCss($this->cronos->getOption('cssUrl') . 'mgr.css');
        $this->addJavascript($this->cronos->getOption('jsUrl') . 'mgr/cronos.js');

        $this->addHtml('
            <script type="text/javascript">
                Ext.onReady(function() {
                    cronos.config = '.$this->modx->toJSON($this->cronos->config).';
                });
            </script>
        ');

        parent::initialize();
    }

    public function getLanguageTopics(): array
    {
        return array('cronos:default');
    }

    public function checkPermissions(): bool
    {
        return true;
    }
}
