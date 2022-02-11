import { iPageType } from './i-page-type';

export class AppSettings
{
	public static readonly pageType : { [key:string] : iPageType } =
	{
		empty 		: { name : "empty" },
		staff 		: { name : "staff" },
		owner 		: { name : "owner" },
		super		: { name : "super" }
	}
}
