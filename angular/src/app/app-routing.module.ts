import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

//import { AppSettings } from './core/app-settings';

import { Page404Component } from './standard/page404/page404.component';
import { LoginComponent } from './standard/login/login.component';
import { ProjectComponent } from './project/project.component';
import { AccessComponent } from './access/access.component';
import { HomeComponent } from './home/home.component';

const routes: Routes =
[
	{
		path: "login",
		component: LoginComponent
	},
	// {
	// 	path: "home",
	// 	loadChildren: "./home/home.module#HomeModule"
	// },
	{
		path: "home",
		component: HomeComponent
	},
	{
		path: "dashboard",
		loadChildren: "./dashboard/dashboard.module#DashboardModule"
	},
	{
		path: "account",
		loadChildren: "./account/account.module#AccountModule"
	},
	{
		path: "event",
		loadChildren: "./event/event.module#EventModule"
	},
	{
		path: "access",
		component: AccessComponent
	},
	{
		path: "project",
		component: ProjectComponent
	},
	{
		path: "support-ticket",
		loadChildren: "./support-ticket/support-ticket.module#SupportTicketModule"
	},
	{
		path: "behavior-base-safety",
		loadChildren: "./behavior-base-safety/behavior-base-safety.module#BehaviorBaseSafetyModule"
	},
	{
		path: "environment-health-safety",
		loadChildren: "./environment-health-safety/environment-health-safety.module#EnvironmentHealthSafetyModule"
	},
	{
		path: "backoffice",
		loadChildren: "./backoffice/backoffice.module#BackOfficeModule"
	},
	
	/** end of tempoery links */
	{ path: 'project/change-management', loadChildren: () => import('./project/change-management/change-management.module').then(m => m.ChangeManagementModule) },
	{ path: 'project/commission', loadChildren: () => import('./project/commission/commission.module').then(m => m.CommissionModule) },
	{ path: 'project/constructibility', loadChildren: () => import('./project/constructibility/constructibility.module').then(m => m.ConstructibilityModule) },
	{ path: 'project/construction', loadChildren: () => import('./project/construction/construction.module').then(m => m.ConstructionModule) },
	{ path: 'project/drawing', loadChildren: () => import('./project/drawing/drawing.module').then(m => m.DrawingModule) },
	{ path: 'project/engineering', loadChildren: () => import('./project/engineering/engineering.module').then(m => m.EngineeringModule) },
	{ path: 'project/health-safety-environment', loadChildren: () => import('./project/health-safety-environment/health-safety-environment.module').then(m => m.HealthSafetyEnvironmentModule) },
	{ path: 'project/hse', loadChildren: () => import('./project/hse/hse.module').then(m => m.HseModule) },
	{ path: 'project/human-resources', loadChildren: () => import('./project/human-resources/human-resources.module').then(m => m.HumanResourcesModule) },
	{ path: 'project/it', loadChildren: () => import('./project/it/it.module').then(m => m.ItModule) },
	{ path: 'project/labour-relations', loadChildren: () => import('./project/labour-relations/labour-relations.module').then(m => m.LabourRelationsModule) },
	{ path: 'project/modularization', loadChildren: () => import('./project/modularization/modularization.module').then(m => m.ModularizationModule) },
	{ path: 'project/project-management', loadChildren: () => import('./project/project-management/project-management.module').then(m => m.ProjectManagementModule) },
	{ path: 'project/quality', loadChildren: () => import('./project/quality/quality.module').then(m => m.QualityModule) },
	{ path: 'project/work-packages', loadChildren: () => import('./project/work-packages/work-packages.module').then(m => m.WorkPackagesModule) },
	
	/** health safety environment */
	{ path: 'project/health-safety-environment/drug-alcohol', loadChildren: () => import('./project/health-safety-environment/drug-alcohol/drug-alcohol.module').then(m => m.DrugAlcoholModule) },
	{ path: 'project/health-safety-environment/crisis-management', loadChildren: () => import('./project/health-safety-environment/crisis-management/crisis-management.module').then(m => m.CrisisManagementModule) },
	{ path: 'project/health-safety-environment/emergency-response-plan', loadChildren: () => import('./project/health-safety-environment/emergency-response-plan/emergency-response-plan.module').then(m => m.EmergencyResponsePlanModule) },
	{ path: 'project/health-safety-environment/env-design-criteria', loadChildren: () => import('./project/health-safety-environment/env-design-criteria/env-design-criteria.module').then(m => m.EnvDesignCriteriaModule) },
	{ path: 'project/health-safety-environment/env-monitoring-plan', loadChildren: () => import('./project/health-safety-environment/env-monitoring-plan/env-monitoring-plan.module').then(m => m.EnvMonitoringPlanModule) },
	{ path: 'project/health-safety-environment/env-protection-plan', loadChildren: () => import('./project/health-safety-environment/env-protection-plan/env-protection-plan.module').then(m => m.EnvProtectionPlanModule) },
	{ path: 'project/health-safety-environment/early-safety', loadChildren: () => import('./project/health-safety-environment/early-safety/early-safety.module').then(m => m.EarlySafetyModule) },
	{ path: 'project/health-safety-environment/greenhouse-gas', loadChildren: () => import('./project/health-safety-environment/greenhouse-gas/greenhouse-gas.module').then(m => m.GreenhouseGasModule) },
	{ path: 'project/health-safety-environment/medical-management', loadChildren: () => import('./project/health-safety-environment/medical-house/medical-house.module').then(m => m.MedicalHouseModule) },
	{ path: 'project/health-safety-environment/medical-management', loadChildren: () => import('./project/health-safety-environment/medical-management/medical-management.module').then(m => m.MedicalManagementModule) },
	{ path: 'project/health-safety-environment/permit-register', loadChildren: () => import('./project/health-safety-environment/permit-register/permit-register.module').then(m => m.PermitRegisterModule) },
	{ path: 'project/health-safety-environment/rehabilitation', loadChildren: () => import('./project/health-safety-environment/rehabilitation/rehabilitation.module').then(m => m.RehabilitationModule) },
	{ path: 'project/health-safety-environment/security-management', loadChildren: () => import('./project/health-safety-environment/security-management/security-management.module').then(m => m.SecurityManagementModule) },
	{ path: 'project/health-safety-environment/site-healths', loadChildren: () => import('./project/health-safety-environment/site-healths/site-healths.module').then(m => m.SiteHealthsModule) },
	{ path: 'project/health-safety-environment/wate-management', loadChildren: () => import('./project/health-safety-environment/waste-management/waste-management.module').then(m => m.WasteManagementModule) },
	
	/*
	{
		path: "purchase",
		loadChildren: "./purchase/purchase.module#PurchaseModule",
		data: { pageType: AppSettings.pageType.empty }
	},
	{
		path: "",
		loadChildren: "./lead/lead.module#LeadModule",
		data: { pageType: AppSettings.pageType.buyer }
	},*/
	{
		path : "**",
		component : Page404Component
	}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
