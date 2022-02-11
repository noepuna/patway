import { iFilter } from '.';

export interface iSearchRequest
{
	param ?:
	{
		limit ?: number
		filter ?: iFilter[]
	}
}
