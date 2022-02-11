// This file can be replaced during build by using the `fileReplacements` array.
// `ng build --prod` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

let baseUrl = "http://localhost/patway";

export const environment =
{
	production: false,
	_API_URL_AUTH : baseUrl + "/api/auth/",
	_API_URL_ACCOUNT : baseUrl + "/api/account/",
	_API_URL_MESSAGING : baseUrl + "/api/messaging/",
	_API_URL_NOTIFICATION : baseUrl + "/api/messaging/notification/",
	_API_URL_EVENT : baseUrl + "/api/event/",
	_API_URL_BBS_OBSERVATION : baseUrl + "/api/behavior-base-safety/observation/",
	_API_URL_SUPPORT_TICKET : baseUrl + "/api/support-ticket/",
	_API_URL_OFFICE_STAFF : baseUrl + "/api/office/staff/",
	_API_URL_OFFICE_ADMIN : baseUrl + "/api/office/admin/",
	_API_URL_EHS_OBSERVATION : baseUrl + "/api/environment-health-safety/messages/",
	_API_URL_EHS_OBSERVATION_ADMIN : baseUrl + "/api/environment-health-safety/",
	_API_URL_OFFICE : baseUrl + "/api/office/",
	/*
	_API_URL_LEAD : baseUrl + "/api/lead/",
	_API_URL_MERCHANT_LEAD : baseUrl + "/api/merchant/lead/",
	_API_URL_BUYER : "/api/buyer",
	_API_URL_PURCHASE : "/api/purchase",

	_API_URL_ITEM : baseUrl + "/api/item/",
	_API_URL_ITEM_IMAGE : baseUrl + "/api/item/images/",
	_API_URL_CART : baseUrl + "/api/cart/",*/
};

/*
 * For easier debugging in development mode, you can import the following file
 * to ignore zone related error stack frames such as `zone.run`, `zoneDelegate.invokeTask`.
 *
 * This import should be commented out in production mode because it will have a negative impact
 * on performance if an error is thrown.
 */
// import 'zone.js/dist/zone-error';  // Included with Angular CLI.
