<script type="text/javascript">
	function removeMedia(index) {
		// table row
		jQuery('#user_medias_' + index).remove();
		// hidden variables
		jQuery('#user_medias_' + index + '_mediaid').remove();
		jQuery('#user_medias_' + index + '_mediatype').remove();
		jQuery('#user_medias_' + index + '_mediatypeid').remove();
		jQuery('#user_medias_' + index + '_period').remove();
		jQuery('#user_medias_' + index + '_sendto').remove();
		removeVarsBySelector(null, 'input[id^="user_medias_' + index + '_sendto_"]');
		jQuery('#user_medias_' + index + '_severity').remove();
		jQuery('#user_medias_' + index + '_active').remove();
		jQuery('#user_medias_' + index + '_description').remove();
	}

	jQuery(function($) {
		var $autologin_cbx = $('#autologin'),
			$autologout_cbx = $('#autologout_visible'),
			$autologout_txt = $('#autologout'),
			is_profile = <?= $this->data['is_profile'] ?>;

		$autologin_cbx.on('click', function() {
			if (this.checked) {
				$autologout_cbx.prop('checked', false);
			}
			$autologout_txt.prop('disabled', this.checked || !$autologin_cbx.prop('checked'));
		});

		$autologout_cbx.on('click', function() {
			if (this.checked) {
				$autologin_cbx.prop('checked', false);
			}
			$autologout_txt.prop('disabled', !this.checked);
		});

		$('form[name="user_form"]').submit(function() {
			var fields_to_trim = ['#password1', '#password2', '#url', '#refresh'];

			if (!is_profile) {
				fields_to_trim.push('#alias', '#name', '#surname');
			}

			$(this).trimValues(fields_to_trim);
		});

		if (is_profile) {
			$('#messages_enabled').on('change', function() {
				$('input, button, select', $('#messagingTab'))
					.not('[name="messages[enabled]"]')
					.prop('disabled', !this.checked);
			}).trigger('change');
		}
	});
</script>
