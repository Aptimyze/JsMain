<?php
/**
 * @interface Defines template for EditProfile Components. 
 * */
interface EditComponent {
	/** It assigns all the values and creates dropdowns for Edit layer form
	 * */
	public function display();
	/** 
	 * It is called when user clicks on save button
	 * */
	public function submit();
	/**
	 * @returns string Template name for this component
	 * */
	public function getTemplateName();
	/**
	 * $returns string Heading of the layer
	 * */
	public function getLayerHeading();
}
