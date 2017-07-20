<?php

namespace App;

use Slim\Views\PhpRenderer;

// Renderer implements a custom view engine featuring template inheritance
// based on the Slim PhpRenderer engine.
class Renderer extends PhpRenderer
{
	public static $parent = NULL;

	// fetch overrides the original method from the PhpRenderer to execute
	// the rendering loop on top of the original rendering method.
	public function fetch($template, array $data = [])
	{
		// Start by setting the parent to NULL.
		static::$parent = NULL;

		do {
			// Render the requested template.
			$output = parent::fetch($template, $data);

			// Set the next template to be the parent defined in
			// the view.
			$template = static::$parent;

			// Reset the parent to prevent endless loops.
			static::$parent = NULL;

			// Add the current output to the data so the next
			// template can display it.
			$data['output'] = $output;

			// Start over while there is a template to execute.
		} while ($template !== NULL);

		return $output;
	}

	// extend is called by views to set the parent template variable.
	public static function extend($view)
	{
		static::$parent = $view;
	}
}
