import { Directive, ElementRef, HostListener } from '@angular/core';
import { Location } from '@angular/common';





@Directive({
  selector: '[appBackNav]'
})
export class BackNavDirective
{
	@HostListener('click') onMouseLeave()
	{
		this.navigateBack();
	}





	constructor( private $_el: ElementRef, private $_location : Location )
	{

	}





	private navigateBack()
	{
		this.$_location.back();
	}
}
