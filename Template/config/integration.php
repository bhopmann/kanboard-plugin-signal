<h3><img src="<?= $this->url->dir() ?>plugins/Signal/Asset/signal-icon.png"/>&nbsp;Signal</h3>
<div class="panel">

    <?= $this->form->label(t('Temporary directory (for processing attachments)'), 'signal_tmp_dir') ?>
    <?= $this->form->text('signal_tmp_dir', $values, array(), array('placeholder="/tmp"')) ?>

    <?= $this->form->label(t('Path to Java installation (JAVA_HOME)'), 'signal_java_home_path') ?>
    <?= $this->form->text('signal_java_home_path', $values) ?>

    <?= $this->form->label(t('Path to signal-cli interface (with optional commands like --dbus or --dbus-system)'), 'signal_cli_path') ?>
    <?= $this->form->text('signal_cli_path', $values) ?>

    <p class="form-help"><a href="https://github.com/AsamK/signal-cli" target="_blank"><?= t('Help on signal-cli integration') ?></a></p>

    <?= $this->form->label(t('Path to signal-cli config directory'), 'signal_cli_config') ?>
    <?= $this->form->text('signal_cli_config', $values) ?>

    <?= $this->form->label(t('Signal username'), 'signal_cli_user') ?>
    <?= $this->form->text('signal_cli_user', $values) ?>

    <?= $this->form->hidden('signal_forward_attachments', array('signal_forward_attachments' => 0)) ?>
    <?= $this->form->checkbox('signal_forward_attachments', t('Sent attachments along with notification'), 1, isset($values['signal_forward_attachments']) && $values['signal_forward_attachments'] == 1) ?>

    <p class="form-help"><a href="https://github.com/stratmaster/kanboard-plugin-signal" target="_blank"><?= t('Help on Signal integration') ?></a></p>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue">
    </div>
</div>
