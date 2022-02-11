import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { RequirementsListComponent } from './requirements-list/requirements-list.component';
import { RequirementPreviewComponent } from './requirement-preview/requirement-preview.component';
import { CreateComponent } from './create/create.component';
import { UpdateComponent } from './update/update.component';
import { ViewComponent } from './view/view.component';
import { ListComponent } from './list/list.component';





const routes: Routes =
[
	{
		path: "create/:ehs_id",
		component: CreateComponent
	},
	{
		path: "update/:message_id",
		component: UpdateComponent
	},
	{
		path: "view/:id",
		component: ViewComponent
	},
	{
		path: "list",
		component: ListComponent
	},
	{
		path: ":ehs_id",
		component: RequirementPreviewComponent
	},
	{
		path: "",
		component: RequirementsListComponent
	}
];

@NgModule({
	imports: [RouterModule.forChild(routes)],
	exports: [RouterModule]
})
export class EnvironmentHealthSafetyRoutingModule { }
