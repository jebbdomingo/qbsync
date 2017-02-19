<?php
/**
 * Nucleon Plus
 *
 * @package     Nucleon Plus
 * @copyright   Copyright (C) 2015 - 2020 Nucleon Plus Co. (http://www.nucleonplus.com)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/jebbdomingo/nucleonplus for the canonical source repository
 */

class ComQbsyncTemplateHelperEditor extends ComKoowaTemplateHelperEditor
{
    public function display($config = array())
    {
        $config = new KObjectConfigJson($config);
        $config->append(array(
            'name' => 'description',
            'config' => array(
                'language'             => 'en',
                'contentsLanguage'     => 'en',
                'extraAllowedContent'  => 'hr[id]',
                'toolbarCanCollapse'   => false,
                'baseHref'             => JUri::root(),
                'extraPlugins'         => 'autocorrect,autolink,autogrow',
                'autoGrow_bottomSpace' => 50,
                'removePlugins'        => 'resize',
                'toolbar'              => array(
                    array('name' => 'basicstyles', 'items' => array('Format', 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'Blockquote')),
                    array('name' => 'links', 'items' => array('Link', 'Unlink', 'Image', 'Table', 'HorizontalRule')),
                    array('name' => 'paragraph', 'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent')),
                    array('name' => 'editing', 'items' => array('Align', 'Paste', 'PasteFromWord', '-', 'Undo', 'Redo'))
                ),
            )
        ));

        $html = '
        <script type="text/javascript">
            function jInsertEditorText(text, editor)
            {
                CKEDITOR.instances["introtext"].insertHtml(text);
            }
        </script>
        ';

        // Collapsible second row toolbar
        $html .= '
            <script>
                CKEDITOR.on("instanceReady", function(e) {

                    function switchVisibilityAfter1stRow(toolbox, show)
                    {
                        var elements     = toolbox.getChildren();
                        var elementIndex = 2; // Secondary row index
                        var secondRow    = elements.getItem(elementIndex);
                        var breakElement = elements.getItem(elementIndex - 1);

                        if (show) {
                            secondRow.show();
                            breakElement.show();
                        } else {
                            secondRow.hide();
                            breakElement.hide();
                        }
                    }

                    var editor = e.editor;
                    var collapser = (function()
                    {
                        try
                        {
                            var firstToolbarId = editor.toolbox.toolbars[0].id;
                            var firstToolbar = CKEDITOR.document.getById(firstToolbarId);
                            var toolbox = firstToolbar.getParent();
                            var collapser = toolbox.getNext();
                            return collapser;
                        }
                        catch (e) {}
                    })();

                    // Copied from editor/_source/plugins/toolbar/plugin.js & modified
                    editor.addCommand("toolbarCollapse",
                    {

                        exec : function( editor )
                        {
                            if (collapser == null) return;

                            var toolbox = collapser.getPrevious(),
                            contents = editor.ui.space( "contents" ),
                            toolboxContainer = toolbox.getParent(),
                            contentHeight = parseInt( contents.$.style.height, 10 ),
                            previousHeight = toolboxContainer.$.offsetHeight,

                            collapsed = toolbox.hasClass("iterate_tbx_hidden");//!toolbox.isVisible();

                            if (!collapsed)
                            {
                                switchVisibilityAfter1stRow(toolbox, false);    // toolbox.hide();
                                toolbox.addClass("iterate_tbx_hidden");
                                if (!toolbox.isVisible()) toolbox.show(); // necessary 1st time if initially collapsed

                                collapser.addClass( "cke_toolbox_collapser_min" );
                                collapser.setAttribute( "title", editor.lang.toolbarExpand );
                            }
                            else
                            {
                                switchVisibilityAfter1stRow(toolbox, true);    // toolbox.show();
                                toolbox.removeClass("iterate_tbx_hidden");

                                collapser.removeClass("cke_toolbox_collapser_min");
                                collapser.setAttribute("title", editor.lang.toolbarCollapse);
                            }

                            // Update collapser symbol.
                            collapser.getFirst().setText( collapsed ?
                                "\u25B2" : // BLACK UP-POINTING TRIANGLE
                                "\u25C0"); // BLACK LEFT-POINTING TRIANGLE

                            var dy = toolboxContainer.$.offsetHeight - previousHeight;
                            contents.setStyle("height", (contentHeight - dy) + "px");

                            editor.fire("resize");
                        },

                        modes : {
                            wysiwyg : 1,
                            source : 1
                        }
                    } )

                    // Make sure second row toolbar is initially collapsed
                    editor.execCommand("toolbarCollapse");
                });
            </script>
        ';

        // Editor
        $html .= $this->getObject('com:ckeditor.controller.editor')->render($config);

        // Additional config
        $html .= '
            <script type="text/javascript">
                kQuery(document).ready(function($) {
                    var height = $(window).height();
                    CKEDITOR.config.autoGrow_maxHeight = height - 230;
                });
            </script>
        ';

        // Buttons
        if (!$config->config->readOnly) {
            $html .= $this->_renderButtons();
        }

        return $html;
    }

    protected function _renderButtons()
    {
        // Setup textman editor
        ComTextmanEditor::setup();

        $buttons  = JEditor::getInstance()->getButtons('introtext');
        $excluded = array();

        $buttons = array_filter($buttons, function($button) use($excluded) {
            if (in_array($button->text, $excluded)) {
                return false;
            } else {
                return $button;
            }
        });

        return JLayoutHelper::render('joomla.editors.buttons', $buttons);
    }
}

/**
 * Wrap JPluginHelper to ensure there's always a default editor
 */
class ComTextmanEditor extends JPluginHelper
{
    public static function setup()
    {
        $hasNoneEditor = false;

        foreach (static::$plugins as $plugin)
        {
            // "Editor - None" is the default/fallback editor
            if ($plugin->type == 'editors' && $plugin->name == 'none') {
                $hasNoneEditor = true;
            }
        }

        // Inject "none" editor at all times
        if (!$hasNoneEditor)
        {
            $noneEditor = (object) array(
                'type'   => 'editors',
                'name'   => 'none',
                'params' => ''
            );

            array_push(static::$plugins, $noneEditor);
        }
    }
}

