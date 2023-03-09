<?php
require_once dirname(dirname(__FILE__)) . '/index.class.php';

class CronosManageManagerController extends CronosBaseManagerController
{

    public function process(array $scriptProperties = []): void
    {
    }

    public function getPageTitle(): string
    {
        return $this->modx->lexicon('cronos');
    }

    public function loadCustomCssJs(): void
    {
        $this->addLastJavascript($this->cronos->getOption('jsUrl') . 'mgr/widgets/manage.panel.js');
        $this->addLastJavascript($this->cronos->getOption('jsUrl') . 'mgr/sections/manage.js');

        $this->addHtml(
            '
            <script type="text/javascript">
                Ext.onReady(function() {
                    MODx.load({ xtype: "cronos-page-manage"});
                });
            </script>
        '
        );
    }

    public function getTemplateFile(): string
    {
        return $this->cronos->getOption('templatesPath') . 'manage.tpl';
    }

}
