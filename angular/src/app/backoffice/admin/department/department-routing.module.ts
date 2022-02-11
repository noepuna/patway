import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { CreateComponent } from './create/create.component';
import { UpdateComponent } from './update/update.component';
import { SearchComponent } from './search/search.component';





const routes: Routes =
[
	{
		path: '',
		redirectTo: 'search',
		pathMatch: 'full'
	},
	{
		path: "create",
		component: CreateComponent
	},
	{
		path: "update/:id",
		component: UpdateComponent
	},
	{
		path: "search",
		component: SearchComponent
	}
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DepartmentRoutingModule { }
