<h2><?= $this->lang->register_title; ?></h2>

<?= $register->open(); ?>
	<fieldset>
		<dl>
			<dt>
				<?= $form_helper->label($this->lang->username, $register->username); ?>
			</dt>
			<dd>
				<?= $form_helper->componentErrorMessage($register->username); ?>
				<?= $register->username; ?>
				<!--<div class="input_note">Username must be between 4 and 15 characters long</div>-->
			</dd>
			<dt>
				<?= $form_helper->label($this->lang->password, $register->password); ?>
			</dt>
			<dd>
				<?= $form_helper->componentErrorMessage($register->password); ?>
				<?= $register->password; ?>
				<!--<div class="input_note">Password must be between 5 and 15 characters long</div>-->
			</dd>
			<dt>
				<?= $form_helper->label($this->lang->retype_password, $register->confirm); ?>
			</dt>
			<dd>
				<?= $form_helper->componentErrorMessage($register->confirm); ?>
				<?= $register->confirm; ?>
			</dd>
			<dt>
				<?= $form_helper->label($this->lang->email, $register->email); ?>
			</dt>
			<dd>
				<?= $form_helper->componentErrorMessage($register->email); ?>
				<?= $register->email; ?>
			</dd>
			<dt>
			</dt>
			<dd>
				<?= $form_helper->componentErrorMessage($register->terms); ?>
				<?= $register->terms; ?>
				<?= $form_helper->label('I accept the ' . $html->link('Terms and Conditions', $terms_url, array('target' => '_blank')), $register->terms); ?>
			</dd>
		</dl>
		<?= $register->submit; ?>
	</fieldset>

<?= $register->close(); ?>
