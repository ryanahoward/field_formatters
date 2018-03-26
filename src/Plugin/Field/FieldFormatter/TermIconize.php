<?php

namespace Drupal\field_formatters\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceLabelFormatter;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\SafeMarkup;

/**
 * Plugin implementation of the 'iconized taxonomy term label' formatter.
 *
 * @FieldFormatter(
 *   id = "taxonomy_term_iconized_label",
 *   label = @Translation("FontAwesome Label"),
 *   description = @Translation("Display an icon with the term."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class TermIconize extends EntityReferenceLabelFormatter {

	/**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    return $summary;
  }
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $entities = parent::getEntitiesToView($items, $langcode);
    foreach ($elements as $delta => $element) {
        if ($entities[$delta]->hasField('field_icon')){
            $name = $entities[$delta]->getName();
            $class = $entities[$delta]->get('field_icon')->value;
            $icon = !empty($class) ? '<i class="'.$class.'"></i>' : '';
            if (isset($element['#url'])) {
                $elements[$delta]['#title'] = SafeMarkup::format($icon.$name, array());
            } else {
                unset($elements[$delta]['#plain_text']);
                $elements[$delta]['#markup'] = SafeMarkup::format($icon.$name, array());
            }
        }  
    }
    return $elements;
  }
    
	public static function isApplicable(FieldDefinitionInterface $field_definition) {
			// By default, formatters are available for all fields.
			return $field_definition->getFieldStorageDefinition()->getSetting('target_type') == 'taxonomy_term';
	}

}
