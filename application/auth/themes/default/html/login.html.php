<h2><?= $this->lang->login_title; ?></h2>

<?= $form_helper->errorMessage($login->getErrorSummary(), $login->getName()); ?>

<?= $login->open(); ?>

	<?= $login->module; ?>
	<?= $login->next; ?>

	<fieldset>
		<legend></legend>
		<dl>
			<dt>
				<?= $form_helper->label($this->lang->username, $login->username);?>
			</dt>
			<dd>
				<?= $login->username; ?>
			</dd>
			<dt>
				<?= $form_helper->label($this->lang->password , $login->password);?>
			</dt>
			<dd>
				<?= $login->password; ?>
			</dd>
			<dt>

			</dt>
			<dd>
				<?= $login->remember; ?>
				<?= $form_helper->label($this->lang->remember_me, $login->remember);?>
			</dd>

			<dt>&nbsp;</dt>
			<dd class="enviar_auth">
				<?php if($submit_image_src): ?>
					<?= $login->submit_image->setSource($submit_image_src); ?>
				<?php else: ?>
					<?= $login->submit; ?>
				<?php endif; ?>
			</dd>
		</dl>
	</fieldset>

<?= $login->close(); ?>