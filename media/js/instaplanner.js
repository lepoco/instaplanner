/*!
  * InstaPlanner 1.0.0 (https://github.com/rapiddev/InstaPlanner)
  * Copyright 2020 RapidDev
  * Licensed under MIT (https://github.com/rapiddev/InstaPlanner/blob/master/LICENSE)
  */
	
	'use strict';

	/**
	* Array.prototype.forEach
	* Lets use foreach
	*/
	Array.prototype.forEach||(Array.prototype.forEach=function(r){let t=this.length;if("function"!=typeof r)throw new TypeError;for(let o=arguments[1],h=0;h<t;h++)h in this&&r.call(o,this[h],h,this)});

	/**
	* escapeHtml
	* Credit: https://stackoverflow.com/a/4835406
	*/
	function escapeHtml( text )
	{
		let map = {
			"&": "&amp;",
			"<": "&lt;",
			">": "&gt;",
			'"': "&quot;",
			"'": "&#039;"
		};

		return text.replace(/[&<>"']/g, function (m)
		{
			return map[m];
		});
	}

	/**
	* Helper function for converting Objects to Arrays after sorting the keys
	* Credit: PiHole
	*/
	function objectToArray( obj )
	{
		let arr = [];
		let idx = [];
		let keys = Object.keys(obj);

		keys.sort(function (a, b) {
			return a - b;
		});

		for (let i = 0; i < keys.length; i++) {
			arr.push(obj[keys[i]]);
			idx.push(keys[i]);
		}

		return [idx, arr];
	}

	/**
	* jsonParse
	* Verifies that a text string can be represented as json
	*/
	function jsonParse(string){if(string==''){return false;}if(/^[\],:{}\s]*$/.test(string.replace(/\\["\\\/bfnrtu]/g,"@").replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,"]").replace(/(?:^|:|,)(?:\s*\[)+/g,""))){return true;}else{return false;}}

	/**
	* DOMContentLoaded
	* The function starts when all resources are loaded
	*/
	document.addEventListener('DOMContentLoaded', function()
	{
		jQuery(function (a)
		{
			a(document).ready(function ()
			{

				themeFunctions();

				if( page_data.pagenow == 'dashboard' )
				{
					page__dashboard();
				}
				else if( page_data.pagenow == 'install' )
				{
					page__install();
				}
				else if( page_data.pagenow == 'login' )
				{
					page__login();
				}
				else if( page_data.pagenow == 'settings' )
				{
					page__settings();
				}
			});
		});
	});

	/**
	* isMobile
	* Are we in mobile mode
	*/
	function isMobile()
	{
		return ( jQuery('html').height() < 992 );
	}

	function themeFunctions()
	{
		/**
		* Fix header height
		*/
		jQuery('.instaplaner__navigation--clone').css( 'height', jQuery('.instaplaner__navigation').outerHeight() + 'px' );

		/**
		* Fix footer height
		*/
		jQuery('.instaplaner__footer--clone').css( 'height', jQuery('.instaplaner__footer').outerHeight() + 'px' );

		/**
		* show_hide_password
		* Hides or shows the password in the supported form field
		*/
		jQuery('#show_hide_password a').on('click', function( event )
		{
			event.preventDefault();

			if(jQuery('#show_hide_password input').attr("type") == "text")
			{
				jQuery('#show_hide_password input').attr('type', 'password');
			}
			else if(jQuery('#show_hide_password input').attr("type") == "password")
			{
				jQuery('#show_hide_password input').attr('type', 'text');
			}
		});
	}

	function page__dashboard()
	{
		new Sortable(
			document.getElementById('instaplaner__posts'),
			{
				handle: ".instaplaner__posts--drag",
				draggable: ".instaplaner__post--dragger",
				easing: "cubic-bezier(1, 0, 0, 1)",
				animation: 300,
				setData: function ( dataTransfer, dragEl )
				{
					let fakeGhost = document.createElement('div');
					fakeGhost.style.opacity = 0;
					dataTransfer.setDragImage(fakeGhost, 0, 0);
				},
				onEnd: function ( evt )
				{
					let order_list = [];
					let posts = jQuery('#instaplaner__posts').children();

					for (let i = 0; i < posts.length; i++)
					{
						order_list.push( jQuery(posts[i]).data()['id'] );
					}
					
					for (let i = order_list.length - 1; i > -1; i--)
					{
						if( order_list[i] == 0)
						{
							order_list.splice(i, 1);
						}
						else
						{
							break;
						}
					}
					jQuery.ajax({
						url: page_data.ajax,
						type: 'post',
						data: {
							action: 'save_reorder',
							nonce: order_nonce,
							account: current_account,
							order: JSON.stringify( order_list )
						},
						success: function(e)
						{
							console.log('The order of the list has been saved');
						},
						fail:function(xhr, textStatus, errorThrown)
						{
							//error
						}
					});
				}
			}
		);

		function addPhoto( id = 0, image = null, desc = null, prepend = false, preview = true )
		{
			let container = document.createElement('div');

			container.style.display = 'none';
			container.dataset.id = id;

			container.classList.add('col-4');
			container.classList.add('col-lg-4');
			//instaplaner__post--preview | instaplaner__post--dragger
			container.classList.add('reveal-item');
			container.classList.add('instaplaner__post');

			if( preview )
				container.classList.add('instaplaner__post--preview');
			else
				container.classList.add('instaplaner__post--dragger');


			let sub = document.createElement('div');
			sub.classList.add('instaplaner__posts--post');

			let dragger = document.createElement('div');
			dragger.classList.add('instaplaner__posts--drag');
			let dragger_icon = document.createElement('div');
			dragger_icon.classList.add('instaplaner__posts--drag__icon');
			dragger.appendChild(dragger_icon); 

			let image_container = document.createElement('div');
			image_container.classList.add('instaplaner__posts__square');

			if(image != null)
				image_container.style.backgroundImage = 'url("' + image + '")';

			let description = document.createElement('div');
			let description_p = document.createElement('p');
			description.classList.add('instaplaner__posts--description');

			description.dataset.id = id;
			description.dataset.image = image;
			description.dataset.description = desc;

			if(desc != null)
			{
				description_p.appendChild(document.createTextNode(desc));
				description.appendChild(description_p);
			}

			sub.appendChild(image_container);
			sub.appendChild(description);
			sub.appendChild(dragger); 
			container.appendChild(sub);

			if(prepend)
				jQuery('#instaplaner__posts').prepend(container);
			else
				jQuery('#instaplaner__posts').append(container);

			jQuery(container).show('slow');
		}

		jQuery('.instaplaner__loader').delay(1000).fadeOut('slow', function()
		{
			for (var i = 0; i < instaplaner_photos.length; i++)
			{
				addPhoto(instaplaner_photos[i][0], instaplaner_photos[i][1], instaplaner_photos[i][2]);
			}


			console.log(instaplaner_photos.length % 3);
			if( instaplaner_photos.length > 0 )
			{
				let blank_posts = ( 6 - instaplaner_photos.length % 3 );

				if( blank_posts == 6 )
					blank_posts = 3;

				for (var i = 0; i < blank_posts; i++)
				{
					addPhoto();
				}
			}
		});

		jQuery('#btn-add-blank').on('click', function(e)
		{
			e.preventDefault();
			
			addPhoto(0, null, 'Blank post', true, !jQuery('#instaplaner_nav_reorder').hasClass('active') );
			jQuery('#instaplaner__addphoto').modal('hide');
		});

		jQuery('#instaplaner_nav_list').on('click', function(e)
		{
			e.preventDefault();

			jQuery(this).addClass('active');
			jQuery('#instaplaner_nav_reorder').removeClass('active');

			jQuery('.instaplaner__toast__mode .toast-body').html('You have entered edit mode');
			jQuery('.instaplaner__toast__mode').toast('show');

			jQuery('.instaplaner__post').removeClass('instaplaner__post--dragger');
			jQuery('.instaplaner__post').addClass('instaplaner__post--preview');
		});

		jQuery('#instaplaner_nav_reorder').on('click', function(e)
		{
			e.preventDefault();

			jQuery('.instaplaner__toast__mode .toast-body').html('You have entered reorder mode');
			jQuery('.instaplaner__toast__mode').toast('show');

			jQuery(this).addClass('active');
			jQuery('#instaplaner_nav_list').removeClass('active');

			jQuery('.instaplaner__post').removeClass('instaplaner__post--preview');
			jQuery('.instaplaner__post').addClass('instaplaner__post--dragger');
		});

		new ClipboardJS('.instaplaner__editphoto--copy', {
			text: function(trigger) {
				//console.log(jQuery('#input-current-description').val());
				return jQuery('#input-current-description').val();
			}
		});

		jQuery(document.body).on('click', '.instaplaner__posts--description', function(e)
		{
			let post_data = jQuery(this).data();
			console.log(post_data);

			if( post_data['id'] != 0 )
			{
				jQuery('#instaplaner__editphoto').modal('show');

				let re = /(?:\.([^.]+))?$/;
				let ext = '.' + re.exec(post_data['image'])[1]
				jQuery('.instaplaner__editphoto--download').attr('download', 'instaplanner-' + post_data['id']  + ext);
				jQuery('.instaplaner__editphoto--download').attr('href', post_data['image']);
				jQuery('.instaplaner__editphoto--delete__confirm, .instaplaner__editphoto--update').attr('data-id', post_data['id']);
				jQuery('#instaplaner__editphoto img').attr('src', post_data['image']);
				jQuery('#input-current-description').val(post_data['description']);
				jQuery('.instaplaner__editphoto--copy').attr('data-clipboard-text', post_data['description']);
			}
		});

		jQuery('#instaplanner_addphoto--upload').on('click', function(e)
		{
			e.preventDefault();
			AddPostQuery();
		});

		jQuery('#instaplanner_addphoto--form').on('submit', function(e)
		{
			e.preventDefault();
			AddPostQuery();
		});

		function AddPostQuery()
		{
			if(jQuery('#login-alert').is(':visible'))
			{
				jQuery('#login-alert').slideToggle();
			}

			let fd = new FormData();
        	fd.append( 'nonce', jQuery('#addphoto_nonce').val() );
        	fd.append( 'action', jQuery('#addphoto_action').val() );
        	fd.append( 'input-account', jQuery('#addphoto_account').val() );
        	fd.append( 'input-description', jQuery('#input-description').val() );
			fd.append( 'input-file', jQuery('#input-file')[0].files[0] );

			jQuery.ajax({
				url: page_data.ajax,
				type: 'post',
				data: fd,
				contentType: false,
				processData: false,
				cache: false,
				success: function(e)
				{
					console.log(e);
					
					if( jsonParse(e) )
					{
						let new_post = JSON.parse(e);
						addPhoto(new_post[0], page_data.baseurl + page_data.media + new_post[1], jQuery('#input-description').val(), true, !jQuery('#instaplaner_nav_reorder').hasClass('active') );

						jQuery('#input-file').val('');
						jQuery('#instaplaner__addphoto').modal('hide');
						jQuery('#input-description').val('');
					}
				},
				fail:function(xhr, textStatus, errorThrown)
				{
					//ełłoł
				}
			});
		}

		jQuery('.instaplaner__editphoto--update').on('click', function(e)
		{
			e.preventDefault();
			let id = jQuery(this).data('id');
			
			jQuery.ajax({
				url: page_data.ajax,
				type: 'post',
				data: {
					action: 'update_post',
					nonce: update_nonce,
					post: id,
					description: jQuery('#input-current-description').val()
				},
				success: function(e)
				{
					console.log(e);

					if( e == 's01')
					{
						jQuery('#instaplaner__editphoto').modal('hide');
						jQuery('.instaplaner__posts--description').filter(function(){
							return jQuery(this).data('id') == id
						}).html('<p>' + jQuery('#input-current-description').val() + '</p>').attr('data-description', jQuery('#input-current-description').val());
					}
				},
				fail:function(xhr, textStatus, errorThrown)
				{
					//error
				}
			});
		});

		jQuery('.instaplaner__editphoto--delete').on('click', function(e)
		{
			e.preventDefault();

			jQuery('.instaplaner__editphoto--alert').slideToggle('fast', function()
			{
				if(jQuery('.instaplaner__editphoto--alert').is(':hidden'))
					jQuery('.instaplaner__editphoto--alert').slideToggle();
			});
			

			console.log('delete');
		});

		jQuery('.instaplaner__editphoto--delete__confirm').on('click', function(e)
		{
			e.preventDefault();
			let id = jQuery(this).data('id');

			jQuery.ajax({
				url: page_data.ajax,
				type: 'post',
				data: {
					action: 'delete_post',
					nonce: delete_nonce,
					post: id
				},
				success: function(e)
				{
					console.log(e);

					if( e == 's01')
					{
						jQuery('#instaplaner__editphoto').modal('hide');

						jQuery('.instaplaner__post').filter(function(){
							return jQuery(this).data('id') == id
						}).remove();
					}
				},
				fail:function(xhr, textStatus, errorThrown)
				{
					//error
				}
			});
			console.log('totaly delete');
		});
	}

	function page__install()
	{
		jQuery('#install-instaplanner').on('click', function(e)
		{
			e.preventDefault();

			jQuery('#install-instaplanner').attr('disabled', 'disabled');
			jQuery('#install-form > div, #install-instaplanner, #install-form-alert').fadeOut(200);
			jQuery('#install-form').slideUp(400, function()
			{
				jQuery('#install-progress').slideDown(400);
			});

			jQuery.ajax({
				url: page_data.baseurl,
				type: 'post',
				data: {
					action: 'setup',
					input_scriptname: jQuery('#input_scriptname').val(),
					input_baseuri: jQuery('#input_baseuri').val(),
					input_db_name: jQuery('#input_db_name').val(),
					input_db_user: jQuery('#input_db_user').val(),
					input_db_host: jQuery('#input_db_host').val(),
					input_db_password: jQuery('#input_db_password').val(),
					input_user_name: jQuery('#input_user_name').val(),
					input_user_password: jQuery('#input_user_password').val(),
				},
				success:function(e)
				{
					console.log(e);
					if(jsonParse(e) && e != null)
					{
						let result = JSON.parse(e);

						if(result.status == 'error')
						{
							window.setTimeout(function()
							{
								jQuery('#install-form, #install-form-alert').hide();
								jQuery('#install-form > div, #install-instaplanner, #install-progress').show();

								jQuery('#install-form-alert > span').html(result.message);

								jQuery('#install-progress').slideUp(400, function()
								{
									jQuery('#install-instaplanner').removeAttr('disabled');
									jQuery('#install-form').slideDown(400, function(e)
									{
										jQuery('#install-form-alert').slideDown(400);
									});
								});
							}, 1500);


						}
						else if(result.status == 'success')
						{
							window.setTimeout(function()
							{
								jQuery('#install-progress').fadeOut(400, function()
								{
									jQuery('#install-done').fadeIn(400);
									window.setTimeout(function(){
										window.location.href = jQuery('#input_baseuri').val()+'dashboard';
									}, 3000);
								});
							}, 1000);
						}
					}
					else
					{
						console.log('error');
					}
				},
				fail: function(xhr, textStatus, errorThrown){
					jQuery('#install-progress').slideUp(400);
					console.log(xhr);
					console.log(textStatus);
					alert(errorThrown);
				}
			});
		});
	}

	function page__login()
	{
		jQuery('#button-form').on('click', function(e)
		{
			e.preventDefault();
			LoginQuery();
		});

		jQuery('#login-form').on('submit', function(e)
		{
			e.preventDefault();
			LoginQuery();
		});

		function LoginQuery()
		{
			if(jQuery('#login-alert').is(':visible'))
			{
				jQuery('#login-alert').slideToggle();
			}

			jQuery.ajax({
				url: page_data.ajax,
				type: 'post',
				data: jQuery("#login-form").serialize(),
				success: function(e)
				{
					console.log(e);

					if(e == 's01')
					{
						location.reload();
					}
					else
					{
						jQuery('#login-alert').slideToggle();
					}
				},
				fail:function(xhr, textStatus, errorThrown){
					jQuery('#login-alert').slideToggle();
				}
			});
		}
	}

	function page__settings()
	{
		let profile_data = {};

		if( add_new_account )
			jQuery('#instaplaner__addaccount').modal('show');

		function insta_profile()
		{
			let profile_url = 'https://www.instagram.com/' + jQuery('#input-account-name').val() + '/?__a=1';

			jQuery('#instaplaner__addaccount--fetch').attr('disabled', 'disabled');

			$.getJSON( profile_url, function( insta_data )
			{
				if ( insta_data.hasOwnProperty('graphql') )
				{
					if( insta_data.graphql.hasOwnProperty('user') )
					{

						profile_data.name = jQuery('#input-account-name').val();
						profile_data.full_name = insta_data.graphql.user.full_name;
						profile_data.biography = insta_data.graphql.user.biography;
						profile_data.url = insta_data.graphql.user.external_url;
						profile_data.avatar = insta_data.graphql.user.profile_pic_url_hd;
						profile_data.followers = insta_data.graphql.user.edge_followed_by.count;
						profile_data.following = insta_data.graphql.user.edge_follow.count;
						profile_data.posts = insta_data.graphql.user.edge_owner_to_timeline_media.count;

						jQuery('.instaplaner__addaccount--preview h3').html( jQuery('#input-account-name').val() );
						jQuery('.instaplaner__addaccount--preview h4').html( insta_data.graphql.user.full_name );
						jQuery('.instaplaner__addaccount--preview p').html( insta_data.graphql.user.biography );
						jQuery('.instaplaner__addaccount--preview a').html( insta_data.graphql.user.external_url );
						jQuery('.instaplaner__addaccount--preview img').attr( 'src', insta_data.graphql.user.profile_pic_url_hd );

						jQuery('#instaplaner__addaccount--followers').html( insta_data.graphql.user.edge_followed_by.count );
						jQuery('#instaplaner__addaccount--following').html( insta_data.graphql.user.edge_follow.count );
						jQuery('#instaplaner__addaccount--posts').html( insta_data.graphql.user.edge_owner_to_timeline_media.count );

						jQuery('.instaplaner__addaccount--preview').slideToggle();
						jQuery('#instaplaner__addaccount--save').removeAttr('disabled');
						
						console.log( insta_data );
					}
				}
			});

			//https://www.instagram.com/themakatka/?__a=1

			
			console.log( jQuery('#input-account-name').val() );

			//https://www.instagram.com/themakatka/?__a=1
		}

		jQuery('#instaplaner__addaccount--form').on('submit', function(e)
		{
			e.preventDefault();

			if( jQuery('#instaplaner__addaccount--fetch').is(":not(:disabled)") )
				insta_profile();
		});
		jQuery('#instaplaner__addaccount--fetch').on('click', function(e)
		{
			e.preventDefault();
			insta_profile();
		});

		jQuery('#instaplaner__addaccount--save').on('click', function(e)
		{
			e.preventDefault();

			if( profile_data.hasOwnProperty('name') )
			{
				profile_data.nonce = register_account_nonce;
				profile_data.action = 'register_account';

				console.log(profile_data);
				
				jQuery.ajax({
					url: page_data.ajax,
					type: 'post',
					data: profile_data,
					success: function(e)
					{
						console.log(e);
					},
					fail:function(xhr, textStatus, errorThrown)
					{
						//no mamy problem
					}
				});
			}
			else
			{
				//no kurde, mamy error
			}
			console.log(profile_data);
		});

		jQuery('#save-settings').on('click', function(e)
		{
			e.preventDefault();
			console.log('save settings');
		});
	}

	