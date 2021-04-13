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
jQuery.fn.mousehold = function(timeout, f)
{
	if (timeout && typeof timeout == 'function')
	{
		f = timeout;
		timeout = 100;
	}
	if (f && typeof f == 'function')
	{
		var timer = 0;
		var fireStep = 0;
		return this.each(function()
		{
			jQuery(this).mousedown(function()
			{
				fireStep = 1;
				var ctr = 0;
				var t = this;
				timer = setInterval(function()
				{
					ctr++;
					f.call(t, ctr);
					fireStep = 2;
				}, timeout);
			})
			clearMousehold = function()
			{
				clearInterval(timer);
				if (fireStep == 1) f.call(this, 1);
				fireStep = 0;
			}
			jQuery(this).mouseout(clearMousehold);
			jQuery(this).mouseup(clearMousehold);
		})
	}
}