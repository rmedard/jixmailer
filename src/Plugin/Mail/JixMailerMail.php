<?php
/**
 * Created by PhpStorm.
 * User: medard
 * Date: 10.06.17
 * Time: 21:42
 */

namespace Drupal\jixmailer\Plugin\Mail;


use Drupal\Core\Mail\MailInterface;
use Drupal\Core\Mail\Plugin\Mail\PhpMail;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class JixMailerMail
 * @package Drupal\jixmailer\Plugin\Mail
 *
 * @Mail(
 *     id = "jixmailer_plugin",
 *     label = @Translation("JixMailer HTML mailer"),
 *     description = @Translation("Sends html emails")
 * )
 */
class JixMailerMail extends PhpMail implements MailInterface, ContainerFactoryPluginInterface {

    /**
     * @var \Drupal\Core\Render\Renderer;
     */
    protected $renderer;

    function __construct(Renderer $renderer){
        $this->renderer = $renderer;
    }

    /**
     * Creates an instance of the plugin.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     *   The container to pull out services used in the plugin.
     * @param array $configuration
     *   A configuration array containing information about the plugin instance.
     * @param string $plugin_id
     *   The plugin ID for the plugin instance.
     * @param mixed $plugin_definition
     *   The plugin implementation definition.
     *
     * @return static
     *   Returns an instance of this plugin.
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
    {
        return new static($container->get('renderer'));
    }

    /**
     * @param array $message
     * @return array
     */
    public function format(array $message) {

        $message = $this->cleanBody($message);
        $message['options']['texte'] = $message['body'];

        $render = [
            '#theme' => 'jixmailer_theme',
            '#message' => $message,
        ];
        $message['body'] = $this->renderer->renderRoot($render);
        return $message;
    }
}