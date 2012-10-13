<h2><?= $this->lang->login_title; ?></h2>

<?= $form_helper->errorMessage($recover->getErrorSummary(), $recover->getName()); ?>

<?= $recover->open(); ?>

	<fieldset>
		<legend></legend>
		<dl>
			<dt>
				<?= $form_helper->label($this->lang->email, $recover->email);?>
			</dt>
			<dd>
				<?= $recover->email; ?>
			</dd>

			<dt>&nbsp;</dt>
			<dd class="enviar_auth">
				<?php if($submit_image_src): ?>
					<?= $recover->submit_image->setSource($submit_image_src); ?>
				<?php else: ?>
					<?= $recover->submit; ?>
				<?php endif; ?>
			</dd>
		</dl>
	</fieldset>

<?= $recover->close(); ?>