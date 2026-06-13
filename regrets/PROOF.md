# Regrets Proof — Condorcet PHP Voting Engine

## Target Repository

**julien-boudry/Condorcet** — Versatile PHP election engine implementing 20+ Condorcet-like voting methods (Schulze, Borda, Copeland, Kemeny-Young, Ranked Pairs, Instant Runoff, Minimax, Smith Set, STV, and more).

**Why this repo:** Condorcet voting algorithms are deeply niche — most developers never encounter Schulze or Kemeny-Young methods. The library has 214 PHP files, ~27K lines of code, 63 test files, and is actively maintained (latest commit May 2026, MIT license, accepts external PRs).

## Refactoring Performed

### Wrapper File Refactoring (regrets/wrappers/election_test.php)

**DECOMPOSITION**: Extracted `createElectionFromInput()` helper function — eliminates 9 instances of duplicated election construction code (add candidates + add votes).

**COHESION**: Grouped all election setup logic into one pure function, separate from method computation.

**NAMING**:
- `extractRanking()` → `extractDeterministicRanking()` — clearly states it strips non-deterministic fields
- `extractPairwise()` → `extractDeterministicPairwise()` — same clarity improvement

**SINGLE RESPONSIBILITY**: Each function now has exactly one job:
- `createElectionFromInput()`: builds election from input data
- `computeMethodRanking()`: runs a method and extracts ranking
- `extractDeterministicRanking()`: strips non-deterministic fields from result
- `extractDeterministicPairwise()`: strips non-deterministic fields from pairwise
- Method-specific functions: thin wrappers that compose the above

**REDUCE COUPLING**: Method functions no longer know about election construction — they delegate to `computeMethodRanking()` which takes a pre-built Election object.

## 3-Way Verification Results

### VERIFICATION 1 — Regrets Fingerprint Validation
All 10 clusters: GREEN ✅

| Cluster | Fingerprint | Status |
|---------|------------|--------|
| schulze-winning | 38ohmir | ✅ PASS |
| borda-count | 38ohmir | ✅ PASS |
| copeland | 38ohmir | ✅ PASS |
| ranked-pairs-winning | 38ohmir | ✅ PASS |
| instant-runoff | 38ohmir | ✅ PASS |
| minimax-winning | 69cmbz2 | ✅ PASS |
| kemeny-young | 38ohmir | ✅ PASS |
| smith-set | 69cmbz2 | ✅ PASS |
| pairwise-comparison | 1hao03o | ✅ PASS |
| combinations-count | ed3si17 | ✅ PASS |

### VERIFICATION 2 — Raw Output Match (KEBENARAN 1)
All 10 clusters: raw outputs IDENTICAL to pre-refactor baseline ✅

### VERIFICATION 3 — Cross-Fingerprint Match (KEBENARAN 2)
All 10 clusters: fingerprints match pre-refactor baseline ✅

## Conclusion

The structural refactoring (decomposition, naming, single responsibility, reduced coupling) is proven safe by 3 independent verification methods. No behavioral change detected — only code structure improved.
