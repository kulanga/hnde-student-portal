<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    private $obj;
    private $layout_view;
    private $title = '';
    private $css_list = array(), $js_list = array();
    private $block_list, $block_new, $block_replace = false;
    private $user;
    private $top_nav;

    function __construct()
    {
        $this->obj =& get_instance();
        $this->layout_view = "layout/default.php";
        // Grab layout from called controller
        if (isset($this->obj->layout_view)) {
            $this->layout_view = $this->obj->layout_view;
        }
    }

    function view($view, $data = null, $return = false)
    {
        // Render template
        $data['content_for_layout'] = $this->obj->load->view($view, $data, true);
        $data['title_for_layout'] = $this->title;

        // Render resources
        $data['js_for_layout'] = '';
        foreach ($this->js_list as $v) {
            $data['js_for_layout'] .= sprintf('<script type="text/javascript" src="%s"></script>', $v);
        }

        $data['css_for_layout'] = '';
        foreach ($this->css_list as $v) {
            $data['css_for_layout'] .= sprintf('<link rel="stylesheet" type="text/css"  href="%s" />', $v);
        }

        $data['user'] = $this->user;
        $data['top_nav'] = $this->top_nav;

        // Render template
        $this->block_replace = true;
        $output = $this->obj->load->view($this->layout_view, $data, $return);

        return $output;
    }
    
    function title($title)
    {
        $this->title = $title;
    }

    function js($item)
    {
        $this->js_list[] = $item;
    }

    function css($item)
    {
        $this->css_list[] = $item;
    }

    function block($name = '')
    {
        if ($name != '') {
            $this->block_new = $name;
            ob_start();
        } else {
            if ($this->block_replace) {
                // If block was overriden in template, replace it in layout
                if (!empty($this->block_list[$this->block_new])) {
                    ob_end_clean();
                    echo $this->block_list[$this->block_new];
                }
            } else {
                $this->block_list[$this->block_new] = ob_get_clean();
            }
        }
    }

    function user($user) {
        $this->user = $user;
    }

    function top_nav($top_nav) {
        $this->top_nav = $top_nav;
    }
}