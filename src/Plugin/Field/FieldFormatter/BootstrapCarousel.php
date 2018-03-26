<?php

namespace Drupal\field_formatters\Plugin\Field\FieldFormatter;

use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;


/**
 * Plugin implementation of the 'bootstrap_carousel' formatter.
 *
 * @FieldFormatter(
 *   id = "bootstrap_carousel",
 *   label = @Translation("Bootstrap Carousel"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class BootstrapCarousel extends ImageFormatter {

  /**
   * @see Drupal\Core\Field\PluginSettingsInterface::defaultSettings()
   */
  public static function defaultSettings() {
    return [
      'interval' => 3,
      'indicators' => 1,
      'controls' => 1,
        ] + parent::defaultSettings();
  }

  /**
   * @see Drupal\Core\Field\FormatterInterface::settingsForm($form, $form_state)
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    //Interval duration in seconds.
    $elements['interval'] = [
      '#type' => 'number',
      '#default_value' => $this->getSetting('interval'),
      '#min' => 0,
      '#max' => 100,
      '#size' => 3,
      '#title' => $this->t('Slide interval'),
      '#description' => $this->t('Indicates slide interval duration in seconds. Set 0 (zero) for no duration.')
    ];
      
    $elements['controls'] = [
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('controls'),
      '#title' => $this->t('Show carousel controls'),
    ];
      
    $elements['indicators'] = [
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('indicators'),
      '#title' => $this->t('Show slide indicators'),
    ];

    return $elements;
  }

  /**
   * @see Drupal\Core\Field\FormatterInterface::settingsSummary()
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();

    $interval = $this->getSetting('interval');
    $summary[] = !empty($interval) ? $this->formatPlural($interval, 'Slide interval duration time: 1 second', 'Slide interval duration time: @count seconds') : $this->t('No interval duration time');
      
    $summary[] = empty($this->getSetting('controls')) ? 'Hide controls' : 'Show controls';
      
    $summary[] = empty($this->getSetting('indicators')) ? 'Hide indicators' : 'Show indicators';

    return $summary;
  }

  /**
   * @see Drupal\Core\Field\FormatterInterface::viewElements($items, $langcode)
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
      
    $elements = parent::viewElements($items, $langcode);
    
    if (count($elements) > 1) {
        $elements = [
          '#theme' => 'bootstrap_carousel_formatter',
          '#entities' => parent::viewElements($items, $langcode),
          '#interval' => $this->getSetting('interval') * 1000,
          '#controls' => $this->getSetting('controls'),
          '#indicators' => $this->getSetting('indicators'),
          '#carousel_id' => 'carousel-' . $items->getEntity()->id().'-'.$items->getName(),
          '#field_name' => $items->getName(),
          '#bundle' => $items->getEntity()->bundle(),
          '#entity_id' => $items->getEntity()->id()
        ];
    }

    return $elements;
  }

}