import { TestBed } from '@angular/core/testing';

import { BehaviorBaseSafetyService } from './behavior-base-safety.service';

describe('BehaviorBaseSafetyService', () => {
  let service: BehaviorBaseSafetyService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(BehaviorBaseSafetyService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
