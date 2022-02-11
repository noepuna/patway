import { Component, OnInit, AfterViewInit, ViewChild, ElementRef, Renderer2 } from '@angular/core';





@Component({
  selector: 'app-dropdown',
  templateUrl: './dropdown.component.html',
  styleUrls: ['./dropdown.component.scss']
})
export class DropdownComponent implements OnInit, AfterViewInit
{
	constructor( private $_renderer : Renderer2 ) { }

	ngOnInit(): void
	{
		//
	}





	@ViewChild("wrapperEl") wrapperEl !: ElementRef;

	ngAfterViewInit()
	{
		let buttonEl = this.wrapperEl.nativeElement.firstChild;

		this.$_renderer.setAttribute( buttonEl, "data-bs-toggle", "dropdown");
	}
}
