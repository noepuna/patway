import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DefaultHeaderNavComponent } from './default-header-nav.component';

describe('DefaultHeaderNavComponent', () => {
  let component: DefaultHeaderNavComponent;
  let fixture: ComponentFixture<DefaultHeaderNavComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ DefaultHeaderNavComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(DefaultHeaderNavComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
