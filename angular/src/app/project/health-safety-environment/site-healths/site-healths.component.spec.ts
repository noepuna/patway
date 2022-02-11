import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SiteHealthsComponent } from './site-healths.component';

describe('SiteHealthsComponent', () => {
  let component: SiteHealthsComponent;
  let fixture: ComponentFixture<SiteHealthsComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ SiteHealthsComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(SiteHealthsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
