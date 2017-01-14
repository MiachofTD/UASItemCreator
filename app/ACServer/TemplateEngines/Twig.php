<?php namespace ACServer\TemplateEngines;

use Twig_Environment;
use Twig_Loader_Filesystem;

class Twig extends Twig_Environment
{
    /**
     * @var array $template_data
     * Contains pre-assigned data to be delivered to the template
     */
    protected $template_data = [];

    /**
     * Bundles the assignment of the loader and the creation of the twig client
     *
     * @param string $directory The directory the templates live in. Default is APP_DIRECTORY . '/app/templates'
     * @param array $options Options to be passed to the twig client
     */
    public function __construct( $directory = '', $options = [] )
    {
        if ( empty( $directory ) ) {
            $directory = APP_DIRECTORY . '/app/templates';
        }

        parent::__construct( new Twig_Loader_Filesystem( $directory ), $options );
    }

    /**
     * Renders the template.
     *
     * @param string $template_name The name of the template file
     * @param array $context Any variables that should be passed to the template. This will be combined
     *   with any pre-set variables. @see assign()
     *
     * @return void
     */
    public function render( $template_name, array $context = [] )
    {
        if ( !empty( $context ) ) {
            $context = array_merge( $context, $this->template_data );
        } else {
            $context = $this->template_data;
        }
        echo parent::render( $template_name, $context );

        //Reset the template variables
        $this->template_data = [];
    }

    /**
     * Assigns variables to template before the rendering or display of said template
     * @internal This function is not required use, but if in the application it makes
     *   logical sense to assign the template a value prior to calling render() or
     *   display(), is is now possible.
     *
     * @param string $name The name of the template variable
     * @param mixed $value The value of the template variable
     */
    public function assign( $name, $value )
    {
        $this->template_data[ $name ] = $value;
    }
}
