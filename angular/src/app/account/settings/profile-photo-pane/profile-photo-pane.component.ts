import { Component, OnInit } from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import { AccountService } from '../../account.service';





@Component({
  selector: 'app-profile-photo-pane',
  templateUrl: './profile-photo-pane.component.html',
  styleUrls: ['./profile-photo-pane.component.scss']
})
export class ProfilePhotoPaneComponent implements OnInit
{
	constructor( private $_sanitizer: DomSanitizer, private $_accountSRV : AccountService ) { }

	ngOnInit(): void
	{

	}





	public selectedPhoto : any ;

	public onFileChange( $event : any )
	{
	    if( $event.target.files.length > 0 )
	    {
	    	if( !this.currentPhoto )
	    	{
	    		this.currentPhoto = this.selectedPhoto;
	    	}

	    	let selectedFile = $event.target.files[0];

	    	this.selectedPhoto = this.$_sanitizer.bypassSecurityTrustResourceUrl(URL.createObjectURL(selectedFile));
	    	this.payload.account.profile_photo = selectedFile;
	    }
	}





	public currentPhoto : any;

	public restoreAttachmentFile()
	{
		this.selectedPhoto = this.currentPhoto;
		this.currentPhoto = null;
	}





	public profilePhotoId : any = null;
	public updateXHR : Promise<object> | null = null;
	public payload : any = { account : {} };
	public formErrors : any;

	public save()
	{
		let updateEvt = ( $response : any ) =>
		{
			this.updateXHR = null;
			this.formErrors = $response?.error;

			this.profilePhotoId = $response?.result?.account?.profile_photo;

			return $response;
		}

		this.updateXHR = this.$_accountSRV.saveProfilePhoto(this.payload).then(updateEvt);
	}
}
