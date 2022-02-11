export interface iComment
{
	id ?: string
	conversation ?: string,
	createdBy ?:
	{
		id ?: string,
		name ?: string
	}
}
