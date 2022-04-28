( function( plugins, editPost, element, components, data, compose ) {
 
	const el = element.createElement;
 
	const { Fragment } = element;
	const { registerPlugin } = plugins;
	const { PluginSidebar, PluginSidebarMoreMenuItem } = editPost;
	const { PanelBody, TextareaControl, Text, CheckboxControl, SelectControl } = components;
	const { withSelect, withDispatch } = data;
 
    

    const telegramIcon = wp.element.createElement(
        'svg', 
        { 
            viewBox: "0 0 240 240"
        },
        wp.element.createElement( 'path',
            { 
                d: "M222.51 19.53c-2.674.083-5.354.78-7.783 1.872-4.433 1.702-51.103 19.78-97.79 37.834C93.576 68.27 70.25 77.28 52.292 84.2 34.333 91.12 21.27 96.114 19.98 96.565c-4.28 1.502-10.448 3.905-14.582 8.76-2.066 2.428-3.617 6.794-1.804 10.53 1.812 3.74 5.303 5.804 10.244 7.69l.152.058.156.048c17.998 5.55 45.162 14.065 48.823 15.213.95 3.134 12.412 40.865 18.65 61.285 1.602 4.226 6.357 7.058 10.773 6.46.794.027 2.264.014 3.898-.378 2.383-.57 5.454-1.924 8.374-4.667l.002-.002c4.153-3.9 18.925-18.373 23.332-22.693l48.27 35.643.18.11s4.368 2.894 10.134 3.284c2.883.195 6.406-.33 9.455-2.556 3.05-2.228 5.25-5.91 6.352-10.71 3.764-16.395 29.428-138.487 33.83-158.837 2.742-10.348 1.442-18.38-3.7-22.872-2.59-2.26-5.675-3.275-8.827-3.395-.394-.015-.788-.016-1.183-.004zm.545 10.02c1.254.02 2.26.365 2.886.91 1.252 1.093 2.878 4.386.574 12.944-12.437 55.246-23.276 111.71-33.87 158.994-.73 3.168-1.752 4.323-2.505 4.873-.754.552-1.613.744-2.884.658-2.487-.17-5.36-1.72-5.488-1.79l-78.207-57.745c7.685-7.266 59.17-55.912 87.352-81.63 3.064-2.95.584-8.278-3.53-8.214-5.294 1.07-9.64 4.85-14.437 7.212-34.79 20.36-100.58 60.213-106.402 63.742-3.04-.954-30.89-9.686-49.197-15.332-2.925-1.128-3.962-2.02-4.344-2.36.007-.01.002.004.01-.005 1.362-1.6 6.97-4.646 10.277-5.807 2.503-.878 14.633-5.544 32.6-12.467 17.965-6.922 41.294-15.938 64.653-24.97 32.706-12.647 65.46-25.32 98.137-37.98 1.617-.75 3.12-1.052 4.375-1.032zM100.293 158.41l19.555 14.44c-5.433 5.32-18.327 17.937-21.924 21.322l2.37-35.762z"
            }
        ),
    );
 
    var MetaCheckboxControl = compose.compose(
        withDispatch( function( dispatch, props ) {
            return {
                setMetaValue: function( metaValue ) {
                    dispatch( 'core/editor' ).editPost(
                        { meta: { [ props.metaKey ]: metaValue } }
                    );
                }
            }
        } ),
        withSelect( function( select, props ) {
            return {
                metaValue: select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ],
            }
        } ) )( function( props ) {
            return el( CheckboxControl, {
                label: props.label,
                checked: props.metaValue,
                onChange: function( content ) {
                    props.setMetaValue( content );
                    msg = TelegramBotParams.string_localize__andsendtotelegram;
                    actual = jQuery(".editor-post-publish-button__button").html();
                    includes = actual.includes( msg );
                    if ( content.toString().localeCompare( 'true' ) == 0 && !includes ) {
                        //jQuery(".editor-post-publish-button__button").html( actual + msg );
                    } else {
                        jQuery(".editor-post-publish-button__button").html( actual.replace( msg, '') );
                    }
                },
            });
        }
    );

    

    function telegrampreview( content, title, slug ) {
        preview = content;
        preview = preview.replace( '%TITLE%', title ) 
        preview = preview.replace( '%LINK%', slug ) 
        jQuery("#telegram_m_send_content_preview").val(preview);
    }
    var MetaTextareaControl = compose.compose(
        withDispatch( function( dispatch, props ) {
            return {
                setMetaValue: function( metaValue ) {
                    dispatch( 'core/editor' ).editPost(
                        { meta: { [ props.metaKey ]: metaValue } }
                    );
                }
            }
        } ),
        withSelect( function( select, props ) {
            textVal = ( select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ] ? select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ] : TelegramBotParams.template );
            telegrampreview( textVal, select( 'core/editor' ).getEditedPostAttribute( 'title' ), select( 'core/editor' ).getPermalink() );
            return {
                metaValue: textVal,
            }
        } ) )( function( props ) {
            return el( TextareaControl, {
                label: props.label,
                value: props.metaValue,
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
            });
        }
    );

    var MetaSelectControl = compose.compose(
        withDispatch( function( dispatch, props ) {
            return {
                setMetaValue: function( metaValue ) {
                    dispatch( 'core/editor' ).editPost(
                        { meta: { [ props.metaKey ]: metaValue } }
                    );
                }
            }
        } ),
        withSelect( function( select, props ) {
            return {
                metaValue: ( select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ] ? select( 'core/editor' ).getEditedPostAttribute( 'meta' )[ props.metaKey ] : TelegramBotParams.target  ),
            }
        } ) )( function( props ) {
            return el( SelectControl, {
                label: props.label,
                value: props.metaValue,
                options: [
                    { value: 0, label: 'Default' },
                    { value: 1, label: 'Users' },
                    { value: 2, label: 'Groups' },
                    { value: 3, label: 'Users, Groups' },
                    { value: 4, label: 'Channel' },
                    { value: 5, label: 'Users, Groups, Channel' },
                ],
                onChange: function( content ) {
                    props.setMetaValue( content );
                },
            });
        }
    );

	registerPlugin( 'telegram-bot-plugin', {
        render: function() {
            return el( Fragment, {},
                el( PluginSidebarMoreMenuItem,
                    {
                        target: 'telegram-bot',
                        icon: telegramIcon,
                    },
                    'Telegram'
                ),
                el( PluginSidebar,
                    {
                        name: 'telegram-bot',
                        icon: telegramIcon,
                        title: 'Telegram',
                    },
                    el(
                        PanelBody, {},
                        el(
                            MetaCheckboxControl,
                            {
                                id: 'telegram_m_send',
                                metaKey: 'telegram_tosend',
                                label: 'Send to Telegram'
                            }
                        )
                    ),
                    el(
                        PanelBody,
                        {
                            title: 'Message'
                        },
                        el(
                            MetaTextareaControl,
                            {
                                id: 'telegram_m_send_content',
                                metaKey: 'telegram_tosend_message',
                                label: 'Compose',
                                rows: 6
                            }
                        ),
                        el(
                            TextareaControl,
                            {
                                id: 'telegram_m_send_content_preview',
                                label: 'Preview',
                                disabled: true,
                                rows: 6
                            }
                        )
                    ),
                    el(
                        PanelBody, {
                            title: 'Target'
                        },
                        el(
                            MetaSelectControl,
                            {
                                id: 'telegram_m_send_target',
                                metaKey: 'telegram_tosend_target'
                            }
                            
                        ),
                    )
                )
            );
        }
    } );
 
} )(
	window.wp.plugins,
	window.wp.editPost,
	window.wp.element,
	window.wp.components,
	window.wp.data,
	window.wp.compose
);