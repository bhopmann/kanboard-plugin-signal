<h3><img src="<?= $this->url->dir() ?>plugins/Signal/Asset/signal-icon.png"/>&nbsp;Signal</h3>
<div class="panel">

    <?= $this->form->label(t('Path to signal-cli config directory'), 'signal_cli_config') ?>
    <?= $this->form->text('signal_cli_config', $values, array(), array('placeholder="'.$this->app->config('signal_cli_config').'"')) ?>
    <p class="form-help">
        <?= t('Leave blank to use global settings ("%s").', $this->app->config('signal_cli_config')) ?>
    </p>

    <?= $this->form->label(t('Signal username'), 'signal_cli_user') ?>
    <?= $this->form->text('signal_cli_user', $values, array(), array('placeholder="'.$this->app->config('signal_cli_user').'"')) ?>
    <p class="form-help">
        <?= t('Leave blank to use global settings ("%s").', $this->app->config('signal_cli_user')) ?>
    </p>

    <p class="form-help"><a href="https://github.com/AsamK/signal-cli" target="_blank"><?= t('Help on signal-cli integration') ?></a></p>

    <?= $this->form->label(t('Signal recipient group'), 'signal_cli_group') ?>
    <?= $this->form->text('signal_cli_group', $values) ?>
    <p class="form-help"><?= t('Group ID in base64 encoding') ?></p>

    <p class="form-help"><a href="https://github.com/stratmaster/kanboard-plugin-signal" target="_blank"><?= t('Help on Signal integration') ?></a></p>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue">
    </div>
</div>
