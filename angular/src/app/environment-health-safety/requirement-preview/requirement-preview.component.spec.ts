import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RequirementPreviewComponent } from './requirement-preview.component';

describe('RequirementPreviewComponent', () => {
  let component: RequirementPreviewComponent;
  let fixture: ComponentFixture<RequirementPreviewComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ RequirementPreviewComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(RequirementPreviewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
