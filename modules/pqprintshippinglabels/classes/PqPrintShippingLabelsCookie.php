<?php
/**
* ProQuality (c) All rights reserved.
*
* DISCLAIMER
*
* Do not edit, modify or copy this file.
* If you wish to customize it, contact us at addons4prestashop@gmail.com.
*
* @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
* @copyright 2015-2016 ProQuality
* @license   Do not edit, modify or copy this file
*/

class PqPrintShippingLabelsCookie
{
	public $name;

	public $parent_cookie;

	public $cookie;

	public function __construct($name, $expire = null)
	{
		$this->name = $name;

		$this->parent_cookie = new Cookie($this->name, '', $expire);

		if (!isset($this->parent_cookie->{$this->name}))
			$this->parent_cookie->{$this->name} = serialize(array());

		$this->cookie = unserialize($this->parent_cookie->{$this->name});
	}

	public function set($key, $value)
	{
		$this->cookie[$key] = $value;
		return $this->write();
	}

	/**
	 * this is used only if the target is array
	 */
	public function append($key, $value)
	{
		$get = $this->get($key);
		if (is_array($get))
			$this->cookie[$key][] = $value;
		else
		{
			$this->cookie[$key] = array();
			$this->cookie[$key][] = $value;
		}

		return $this->write();
	}

	public function get($key)
	{
		if (isset($this->cookie[$key]))
			return $this->cookie[$key];

		return false;
	}

	public function exists($key)
	{
		return (isset($this->cookie[$key]));
	}

	public function destroy()
	{
		if (isset($this->parent_cookie->{$this->name}))
			$this->parent_cookie->{$this->name} = null;
	}

	public function getCookies()
	{
		return $this->cookie;
	}

	public function write()
	{
		$this->parent_cookie->{$this->name} = serialize($this->cookie);
	}
}


?>