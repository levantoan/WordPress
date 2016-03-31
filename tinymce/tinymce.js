(function() {
	tinymce.PluginManager.add('my_mce_button', function( editor, url ) {
		editor.addButton('my_mce_button', {
			icon: 'my-paypal-icon',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Add Paypal Button',
					body: [
						{
							type: 'textbox',
							name: 'email_address',
							label: 'Paypal Email',
							value: 'timbond@truevinepublishing.org'
						},
						{
							type: 'textbox',
							name: 'name_paypal',
							label: 'Name',
							value: ''
						},
						{
							type: 'textbox',
							name: 'amount_paypal',
							label: 'Amount',
							value: ''
						},
						/*
						{
							type: 'listbox',
							name: 'listboxName',
							label: 'List Box',
							'values': [
								{text: 'Option 1', value: '1'},
								{text: 'Option 2', value: '2'},
								{text: 'Option 3', value: '3'}
							]
						}*/
					],
					onsubmit: function( e ) {
						editor.insertContent( '[paypal_button email="' + e.data.email_address + '" name="' + e.data.name_paypal + '" amount="' + e.data.amount_paypal + '"]');
					}
				});
			}
		});
	});
})();