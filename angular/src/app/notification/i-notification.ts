export interface iNotification
{
	id ?: string
	conversation ?: string,
	appComponent ?: string,
	payload ?: any
	createdBy ?:
	{
		id ?: string, name ?: string
	},
	dateCreated ?: string
}
