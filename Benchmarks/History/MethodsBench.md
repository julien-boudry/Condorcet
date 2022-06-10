# v4.0-beta1 Branch

1000 random votes different for each test, variable number of candidates (look at column "set") 

* AMD Ryzen 9 5900X

PHPBench (1.2.5) 
with PHP version 8.1.6, xdebug ❌, opcache ✔ (with JIT Tracing)

```php
    #[Bench\Warmup(1)]
    #[Bench\Iterations(3)]
    #[Bench\Revs(1)]
```
### Non-Proportional

Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+-----------------------------+-------------------+-----------------------------------+------+-----+----------+-------------+----------+
| benchmark                   | subject           | set                               | revs | its | mem_peak | mode        | rstdev   |
+-----------------------------+-------------------+-----------------------------------+------+-----+----------+-------------+----------+
| MethodsNonProportionalBench | benchByCandidates | BordaCount,3                      | 1    | 3   | 4.271mb  | 0.002924s   | ±1.54%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,3                        | 1    | 3   | 3.935mb  | 0.000023s   | ±13.03%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,3                   | 1    | 3   | 3.863mb  | 0.000020s   | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,3   | 1    | 3   | 3.864mb  | 0.000024s   | ±5.27%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,3                   | 1    | 3   | 3.926mb  | 0.001511s   | ±1.33%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,3                  | 1    | 3   | 3.932mb  | 0.004448s   | ±48.14%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,3                    | 1    | 3   | 4.282mb  | 0.000276s   | ±138.24% |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,3      | 1    | 3   | 3.929mb  | 0.002349s   | ±27.04%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,3          | 1    | 3   | 3.932mb  | 0.004415s   | ±1.34%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,3                 | 1    | 3   | 3.864mb  | 0.000024s   | ±4.04%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,3                  | 1    | 3   | 3.864mb  | 0.000024s   | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,3              | 1    | 3   | 3.864mb  | 0.000023s   | ±3.55%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,3             | 1    | 3   | 3.934mb  | 0.001575s   | ±77.80%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,3            | 1    | 3   | 3.932mb  | 0.000203s   | ±135.55% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,3                 | 1    | 3   | 3.865mb  | 0.000027s   | ±1.72%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,3                  | 1    | 3   | 3.865mb  | 0.000028s   | ±2.92%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,3                   | 1    | 3   | 3.865mb  | 0.000029s   | ±1.64%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,5                      | 1    | 3   | 4.815mb  | 0.002170s   | ±28.16%  |
| MethodsNonProportionalBench | benchByCandidates | Copeland,5                        | 1    | 3   | 4.755mb  | 0.000030s   | ±3.07%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,5                   | 1    | 3   | 4.753mb  | 0.000024s   | ±1.94%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,5   | 1    | 3   | 4.755mb  | 0.000030s   | ±1.55%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,5                   | 1    | 3   | 4.785mb  | 0.002025s   | ±8.04%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,5                  | 1    | 3   | 4.817mb  | 0.008432s   | ±30.49%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,5                    | 1    | 3   | 4.835mb  | 0.002584s   | ±63.87%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,5      | 1    | 3   | 4.815mb  | 0.001811s   | ±52.88%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,5          | 1    | 3   | 4.786mb  | 0.003948s   | ±1.11%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,5                 | 1    | 3   | 4.755mb  | 0.000031s   | ±23.38%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,5                  | 1    | 3   | 4.755mb  | 0.000032s   | ±3.94%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,5              | 1    | 3   | 4.755mb  | 0.000030s   | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,5             | 1    | 3   | 4.837mb  | 0.002302s   | ±105.98% |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,5            | 1    | 3   | 4.840mb  | 0.000156s   | ±123.44% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,5                 | 1    | 3   | 4.816mb  | 0.000036s   | ±51.31%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,5                  | 1    | 3   | 4.824mb  | 0.000126s   | ±132.19% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,5                   | 1    | 3   | 4.819mb  | 0.005626s   | ±91.76%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,6                      | 1    | 3   | 5.229mb  | 0.002457s   | ±5.62%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,6                        | 1    | 3   | 5.269mb  | 0.002489s   | ±49.30%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,6                   | 1    | 3   | 5.260mb  | 0.001559s   | ±78.69%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,6   | 1    | 3   | 5.264mb  | 0.003050s   | ±73.92%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,6                   | 1    | 3   | 5.229mb  | 0.002449s   | ±3.15%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,6                  | 1    | 3   | 5.262mb  | 0.014201s   | ±13.25%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,6                    | 1    | 3   | 5.277mb  | 0.001201s   | ±1.27%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,6      | 1    | 3   | 5.229mb  | 0.001869s   | ±2.08%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,6          | 1    | 3   | 5.230mb  | 0.004348s   | ±1.65%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,6                 | 1    | 3   | 5.261mb  | 0.000115s   | ±133.60% |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,6                  | 1    | 3   | 5.260mb  | 0.001349s   | ±68.59%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,6              | 1    | 3   | 5.201mb  | 0.000027s   | ±5.05%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,6             | 1    | 3   | 5.295mb  | 0.002948s   | ±42.04%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,6            | 1    | 3   | 5.228mb  | 0.000107s   | ±14.62%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,6                 | 1    | 3   | 5.201mb  | 0.000044s   | ±18.40%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,6                  | 1    | 3   | 5.201mb  | 0.000046s   | ±3.75%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,6                   | 1    | 3   | 5.267mb  | 0.009940s   | ±22.86%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,7                      | 1    | 3   | 5.674mb  | 0.002877s   | ±4.27%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,7                        | 1    | 3   | 5.646mb  | 0.000032s   | ±5.10%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,7                   | 1    | 3   | 5.643mb  | 0.000025s   | ±1.91%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,7   | 1    | 3   | 5.646mb  | 0.000030s   | ±7.44%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,7                   | 1    | 3   | 5.674mb  | 0.002809s   | ±2.26%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,7                  | 1    | 3   | 5.743mb  | 0.014805s   | ±4.45%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,7                    | 1    | 3   | 5.723mb  | 0.010580s   | ±6.88%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,7      | 1    | 3   | 5.705mb  | 0.002164s   | ±48.66%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,7          | 1    | 3   | 5.674mb  | 0.005069s   | ±3.11%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,7                 | 1    | 3   | 5.705mb  | 0.001430s   | ±68.26%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,7                  | 1    | 3   | 5.706mb  | 0.000121s   | ±132.79% |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,7              | 1    | 3   | 5.646mb  | 0.000030s   | ±4.56%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,7             | 1    | 3   | 5.726mb  | 0.002715s   | ±34.92%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,7            | 1    | 3   | 5.687mb  | 0.000184s   | ±12.97%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,7                 | 1    | 3   | 5.647mb  | 0.000051s   | ±0.93%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,7                  | 1    | 3   | 5.647mb  | 0.000056s   | ±4.34%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,7                   | 1    | 3   | 5.716mb  | 0.002377s   | ±106.81% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,8                      | 1    | 3   | 6.438mb  | 0.003243s   | ±5.05%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,8                        | 1    | 3   | 6.470mb  | 0.000122s   | ±131.50% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,8                   | 1    | 3   | 6.470mb  | 0.001435s   | ±82.71%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,8   | 1    | 3   | 6.413mb  | 0.000031s   | ±8.12%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,8                   | 1    | 3   | 6.438mb  | 0.002999s   | ±3.19%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,8                  | 1    | 3   | 6.474mb  | 0.018136s   | ±4.09%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,8                    | 1    | 3   | 6.489mb  | 0.104723s   | ±0.40%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,8      | 1    | 3   | 6.439mb  | 0.002409s   | ±9.94%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,8          | 1    | 3   | 6.439mb  | 0.005636s   | ±2.19%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,8                 | 1    | 3   | 6.411mb  | 0.000034s   | ±7.20%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,8                  | 1    | 3   | 6.411mb  | 0.000032s   | ±14.19%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,8              | 1    | 3   | 6.413mb  | 0.000031s   | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,8             | 1    | 3   | 6.465mb  | 0.000189s   | ±16.95%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,8            | 1    | 3   | 6.463mb  | 0.000203s   | ±9.52%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,8                 | 1    | 3   | 6.469mb  | 0.000142s   | ±125.75% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,8                  | 1    | 3   | 6.414mb  | 0.000061s   | ±2.28%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,8                   | 1    | 3   | 6.469mb  | 0.003305s   | ±101.92% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,9                      | 1    | 3   | 6.897mb  | 0.003411s   | ±3.49%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,9                        | 1    | 3   | 6.873mb  | 0.000036s   | ±4.04%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,9                   | 1    | 3   | 6.872mb  | 0.000029s   | ±2.82%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,9   | 1    | 3   | 6.873mb  | 0.000034s   | ±4.04%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,9                   | 1    | 3   | 6.897mb  | 0.003076s   | ±2.97%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,9                  | 1    | 3   | 6.930mb  | 0.021697s   | ±5.03%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,9                    | 1    | 3   | 6.950mb  | 1.160851s   | ±0.82%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,9      | 1    | 3   | 6.897mb  | 0.002320s   | ±5.76%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,9          | 1    | 3   | 6.897mb  | 0.005771s   | ±3.65%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,9                 | 1    | 3   | 6.873mb  | 0.000036s   | ±8.41%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,9                  | 1    | 3   | 6.930mb  | 0.000122s   | ±131.63% |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,9              | 1    | 3   | 6.873mb  | 0.000036s   | ±3.50%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,9             | 1    | 3   | 6.951mb  | 0.000457s   | ±85.52%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,9            | 1    | 3   | 6.943mb  | 0.000356s   | ±12.93%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,9                 | 1    | 3   | 6.876mb  | 0.000076s   | ±3.22%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,9                  | 1    | 3   | 6.876mb  | 0.000079s   | ±2.59%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,9                   | 1    | 3   | 6.928mb  | 0.001263s   | ±103.98% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,10                     | 1    | 3   | 7.344mb  | 0.003596s   | ±2.72%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,10                       | 1    | 3   | 7.321mb  | 0.000037s   | ±3.40%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,10                  | 1    | 3   | 7.321mb  | 0.000030s   | ±3.21%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,10  | 1    | 3   | 7.322mb  | 0.000039s   | ±39.55%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,10                  | 1    | 3   | 7.344mb  | 0.003367s   | ±3.86%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,10                 | 1    | 3   | 7.352mb  | 0.023994s   | ±0.42%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,10                   | 1    | 3   | 7.400mb  | 14.549329s  | ±0.38%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,10     | 1    | 3   | 7.344mb  | 0.002413s   | ±1.69%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,10         | 1    | 3   | 7.344mb  | 0.006122s   | ±0.24%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,10                | 1    | 3   | 7.322mb  | 0.000036s   | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,10                 | 1    | 3   | 7.322mb  | 0.000036s   | ±3.82%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,10             | 1    | 3   | 7.322mb  | 0.000040s   | ±4.32%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,10            | 1    | 3   | 7.402mb  | 0.000546s   | ±6.04%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,10           | 1    | 3   | 7.402mb  | 0.000592s   | ±12.46%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,10                | 1    | 3   | 7.329mb  | 0.000089s   | ±10.25%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,10                 | 1    | 3   | 7.329mb  | 0.000089s   | ±2.30%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,10                  | 1    | 3   | 7.329mb  | 0.000217s   | ±109.63% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,11                     | 1    | 3   | 7.790mb  | 0.003812s   | ±2.17%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,11                       | 1    | 3   | 7.768mb  | 0.000038s   | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,11                  | 1    | 3   | 7.767mb  | 0.000055s   | ±41.31%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,11  | 1    | 3   | 7.769mb  | 0.000038s   | ±2.15%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,11                  | 1    | 3   | 7.790mb  | 0.003964s   | ±0.42%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,11                 | 1    | 3   | 7.798mb  | 0.028704s   | ±1.44%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,11                   | 1    | 3   | 7.849mb  | 193.044781s | ±1.45%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,11     | 1    | 3   | 7.790mb  | 0.002761s   | ±3.59%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,11         | 1    | 3   | 7.790mb  | 0.007038s   | ±3.48%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,11                | 1    | 3   | 7.769mb  | 0.000036s   | ±7.44%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,11                 | 1    | 3   | 7.769mb  | 0.000040s   | ±6.13%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,11             | 1    | 3   | 7.769mb  | 0.000041s   | ±6.94%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,11            | 1    | 3   | 7.866mb  | 0.000817s   | ±1.51%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,11           | 1    | 3   | 7.862mb  | 0.000724s   | ±9.95%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,11                | 1    | 3   | 7.776mb  | 0.000104s   | ±3.36%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,11                 | 1    | 3   | 7.777mb  | 0.000110s   | ±7.03%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,11                  | 1    | 3   | 7.777mb  | 0.000160s   | ±4.44%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,20                     | 1    | 3   | 12.511mb | 0.007010s   | ±0.76%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,20                       | 1    | 3   | 12.503mb | 0.000066s   | ±2.55%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,20                  | 1    | 3   | 12.507mb | 0.000050s   | ±1.63%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,20  | 1    | 3   | 12.504mb | 0.000067s   | ±9.96%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,20                  | 1    | 3   | 12.511mb | 0.007244s   | ±1.16%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,20                 | 1    | 3   | 12.533mb | 0.090596s   | ±1.12%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,20                   | 1    | 3   | 12.474mb | 0.000007s   | ±22.17%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,20     | 1    | 3   | 12.510mb | 0.004687s   | ±1.64%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,20         | 1    | 3   | 12.510mb | 0.012116s   | ±0.28%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,20                | 1    | 3   | 12.503mb | 0.000067s   | ±1.22%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,20                 | 1    | 3   | 12.502mb | 0.000153s   | ±122.93% |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,20             | 1    | 3   | 12.503mb | 0.000066s   | ±2.11%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,20            | 1    | 3   | 12.785mb | 0.011022s   | ±11.77%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,20           | 1    | 3   | 12.802mb | 0.012487s   | ±0.22%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,20                | 1    | 3   | 12.542mb | 0.000431s   | ±1.23%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,20                 | 1    | 3   | 12.542mb | 0.000454s   | ±2.35%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,20                  | 1    | 3   | 12.542mb | 0.000775s   | ±2.63%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,30                     | 1    | 3   | 17.044mb | 0.010831s   | ±0.89%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,30                       | 1    | 3   | 17.047mb | 0.000111s   | ±22.90%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,30                  | 1    | 3   | 17.054mb | 0.000072s   | ±21.70%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,30  | 1    | 3   | 17.048mb | 0.000102s   | ±2.01%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,30                  | 1    | 3   | 17.044mb | 0.010428s   | ±1.02%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,30                 | 1    | 3   | 17.080mb | 0.193523s   | ±4.90%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,30                   | 1    | 3   | 17.007mb | 0.000009s   | ±21.60%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,30     | 1    | 3   | 17.043mb | 0.006859s   | ±1.91%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,30         | 1    | 3   | 17.043mb | 0.018535s   | ±0.59%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,30                | 1    | 3   | 17.046mb | 0.000192s   | ±114.86% |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,30                 | 1    | 3   | 17.045mb | 0.000108s   | ±1.16%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,30             | 1    | 3   | 17.046mb | 0.000108s   | ±2.32%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,30            | 1    | 3   | 17.660mb | 0.101841s   | ±12.55%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,30           | 1    | 3   | 17.667mb | 0.099423s   | ±5.12%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,30                | 1    | 3   | 17.219mb | 0.001361s   | ±3.04%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,30                 | 1    | 3   | 17.216mb | 0.001365s   | ±7.51%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,30                  | 1    | 3   | 17.103mb | 0.002616s   | ±3.81%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,40                     | 1    | 3   | 23.088mb | 0.015801s   | ±0.85%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,40                       | 1    | 3   | 23.098mb | 0.000158s   | ±0.30%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,40                  | 1    | 3   | 23.137mb | 0.000108s   | ±0.88%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,40  | 1    | 3   | 23.100mb | 0.000165s   | ±23.09%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,40                  | 1    | 3   | 23.088mb | 0.014674s   | ±1.13%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,40                 | 1    | 3   | 23.190mb | 0.334868s   | ±5.42%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,40                   | 1    | 3   | 23.045mb | 0.000008s   | ±16.27%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,40     | 1    | 3   | 23.075mb | 0.009288s   | ±2.02%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,40         | 1    | 3   | 23.075mb | 0.025577s   | ±0.95%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,40                | 1    | 3   | 23.089mb | 0.000154s   | ±16.71%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,40                 | 1    | 3   | 23.090mb | 0.000154s   | ±1.90%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,40             | 1    | 3   | 23.089mb | 0.000152s   | ±1.92%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,40            | 1    | 3   | 24.218mb | 0.440535s   | ±11.54%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,40           | 1    | 3   | 24.196mb | 0.459768s   | ±6.19%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,40                | 1    | 3   | 23.269mb | 0.002907s   | ±1.89%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,40                 | 1    | 3   | 23.272mb | 0.003054s   | ±0.85%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,40                  | 1    | 3   | 23.270mb | 0.005822s   | ±1.07%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,50                     | 1    | 3   | 27.651mb | 0.020448s   | ±0.99%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,50                       | 1    | 3   | 27.665mb | 0.000216s   | ±0.88%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,50                  | 1    | 3   | 27.722mb | 0.000133s   | ±0.71%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,50  | 1    | 3   | 27.668mb | 0.000215s   | ±2.19%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,50                  | 1    | 3   | 27.652mb | 0.019335s   | ±0.66%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,50                 | 1    | 3   | 27.784mb | 0.470916s   | ±10.21%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,50                   | 1    | 3   | 27.601mb | 0.000006s   | ±20.20%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,50     | 1    | 3   | 27.631mb | 0.011887s   | ±1.77%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,50         | 1    | 3   | 27.631mb | 0.033005s   | ±0.23%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,50                | 1    | 3   | 27.653mb | 0.000218s   | ±13.74%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,50                 | 1    | 3   | 27.659mb | 0.000212s   | ±1.45%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,50             | 1    | 3   | 27.661mb | 0.000210s   | ±1.33%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,50            | 1    | 3   | 29.349mb | 1.454932s   | ±5.84%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,50           | 1    | 3   | 29.321mb | 1.577493s   | ±11.78%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,50                | 1    | 3   | 27.886mb | 0.005544s   | ±1.19%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,50                 | 1    | 3   | 27.886mb | 0.005489s   | ±3.28%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,50                  | 1    | 3   | 27.887mb | 0.011167s   | ±1.39%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,60                     | 1    | 3   | 32.216mb | 0.025406s   | ±0.36%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,60                       | 1    | 3   | 32.229mb | 0.000284s   | ±1.35%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,60                  | 1    | 3   | 32.303mb | 0.000176s   | ±22.99%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,60  | 1    | 3   | 32.235mb | 0.000286s   | ±2.46%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,60                  | 1    | 3   | 32.216mb | 0.024220s   | ±1.01%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,60                 | 1    | 3   | 32.383mb | 0.774454s   | ±5.71%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,60                   | 1    | 3   | 32.157mb | 0.000006s   | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,60     | 1    | 3   | 32.186mb | 0.014692s   | ±1.33%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,60         | 1    | 3   | 32.186mb | 0.041035s   | ±0.53%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,60                | 1    | 3   | 32.223mb | 0.000283s   | ±2.00%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,60                 | 1    | 3   | 32.221mb | 0.000282s   | ±1.02%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,60             | 1    | 3   | 32.214mb | 0.000281s   | ±5.60%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,60            | 1    | 3   | 34.635mb | 3.743467s   | ±5.62%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,60           | 1    | 3   | 34.706mb | 4.070218s   | ±5.76%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,60                | 1    | 3   | 32.495mb | 0.009350s   | ±1.08%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,60                 | 1    | 3   | 32.497mb | 0.009517s   | ±2.41%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,60                  | 1    | 3   | 32.498mb | 0.019421s   | ±0.57%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,70                     | 1    | 3   | 44.439mb | 0.032305s   | ±1.34%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,70                       | 1    | 3   | 44.434mb | 0.000380s   | ±0.81%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,70                  | 1    | 3   | 44.720mb | 0.000224s   | ±3.00%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,70  | 1    | 3   | 44.462mb | 0.000373s   | ±2.55%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,70                  | 1    | 3   | 44.439mb | 0.030708s   | ±2.96%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,70                 | 1    | 3   | 44.823mb | 1.023154s   | ±6.37%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,70                   | 1    | 3   | 44.361mb | 0.000008s   | ±16.27%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,70     | 1    | 3   | 44.392mb | 0.019544s   | ±1.65%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,70         | 1    | 3   | 44.392mb | 0.052625s   | ±0.48%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,70                | 1    | 3   | 44.420mb | 0.000353s   | ±1.61%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,70                 | 1    | 3   | 44.423mb | 0.000370s   | ±7.45%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,70             | 1    | 3   | 44.422mb | 0.000363s   | ±0.67%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,70            | 1    | 3   | 47.656mb | 6.784191s   | ±1.86%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,70           | 1    | 3   | 47.611mb | 6.588119s   | ±6.27%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,70                | 1    | 3   | 45.530mb | 0.015970s   | ±6.81%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,70                 | 1    | 3   | 45.534mb | 0.016065s   | ±2.66%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,70                  | 1    | 3   | 45.529mb | 0.031048s   | ±0.63%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,80                     | 1    | 3   | 49.285mb | 0.038611s   | ±0.73%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,80                       | 1    | 3   | 49.281mb | 0.000463s   | ±3.47%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,80                  | 1    | 3   | 49.633mb | 0.000269s   | ±14.00%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,80  | 1    | 3   | 49.311mb | 0.000468s   | ±19.71%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,80                  | 1    | 3   | 49.285mb | 0.038735s   | ±1.24%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,80                 | 1    | 3   | 49.632mb | 1.428535s   | ±11.94%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,80                   | 1    | 3   | 49.199mb | 0.000008s   | ±23.18%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,80     | 1    | 3   | 49.233mb | 0.022248s   | ±1.47%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,80         | 1    | 3   | 49.233mb | 0.063431s   | ±0.89%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,80                | 1    | 3   | 49.266mb | 0.000446s   | ±0.49%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,80                 | 1    | 3   | 49.267mb | 0.000454s   | ±2.22%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,80             | 1    | 3   | 49.264mb | 0.000452s   | ±0.82%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,80            | 1    | 3   | 53.517mb | 18.839536s  | ±21.46%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,80           | 1    | 3   | 53.492mb | 16.246016s  | ±14.55%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,80                | 1    | 3   | 50.527mb | 0.022245s   | ±0.63%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,80                 | 1    | 3   | 50.532mb | 0.023183s   | ±2.02%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,80                  | 1    | 3   | 50.534mb | 0.046185s   | ±0.61%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,90                     | 1    | 3   | 54.130mb | 0.046329s   | ±1.19%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,90                       | 1    | 3   | 54.154mb | 0.000574s   | ±2.18%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,90                  | 1    | 3   | 54.497mb | 0.000318s   | ±7.27%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,90  | 1    | 3   | 54.160mb | 0.000607s   | ±3.88%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,90                  | 1    | 3   | 54.130mb | 0.045796s   | ±1.57%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,90                 | 1    | 3   | 54.478mb | 1.483854s   | ±10.63%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,90                   | 1    | 3   | 54.036mb | 0.000007s   | ±31.50%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,90     | 1    | 3   | 54.074mb | 0.026423s   | ±1.50%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,90         | 1    | 3   | 54.074mb | 0.075511s   | ±0.51%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,90                | 1    | 3   | 54.109mb | 0.000619s   | ±7.23%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,90                 | 1    | 3   | 54.107mb | 0.000535s   | ±0.95%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,90             | 1    | 3   | 54.111mb | 0.000554s   | ±0.52%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,90            | 1    | 3   | 59.327mb | 26.526442s  | ±14.83%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,90           | 1    | 3   | 59.269mb | 28.957465s  | ±10.13%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,90                | 1    | 3   | 55.537mb | 0.031824s   | ±1.01%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,90                 | 1    | 3   | 55.537mb | 0.031800s   | ±3.34%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,90                  | 1    | 3   | 55.536mb | 0.063706s   | ±0.29%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,100                    | 1    | 3   | 58.976mb | 0.054073s   | ±1.33%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,100                      | 1    | 3   | 58.999mb | 0.000670s   | ±0.63%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,100                 | 1    | 3   | 59.394mb | 0.000375s   | ±0.65%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,100 | 1    | 3   | 59.010mb | 0.000691s   | ±0.96%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,100                 | 1    | 3   | 58.976mb | 0.052387s   | ±1.49%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,100                | 1    | 3   | 59.436mb | 1.900686s   | ±7.49%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,100                  | 1    | 3   | 58.873mb | 0.000006s   | ±13.61%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,100    | 1    | 3   | 58.916mb | 0.030921s   | ±3.30%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,100        | 1    | 3   | 58.916mb | 0.086447s   | ±0.74%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,100               | 1    | 3   | 58.953mb | 0.000642s   | ±1.26%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,100                | 1    | 3   | 58.950mb | 0.000636s   | ±0.52%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,100            | 1    | 3   | 58.952mb | 0.000641s   | ±0.89%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,100           | 1    | 3   | 65.323mb | 49.233827s  | ±3.59%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,100          | 1    | 3   | 65.547mb | 55.108877s  | ±14.49%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,100               | 1    | 3   | 60.537mb | 0.043607s   | ±0.64%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,100                | 1    | 3   | 60.534mb | 0.043322s   | ±1.61%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,100                 | 1    | 3   | 60.537mb | 0.089272s   | ±6.19%   |
+-----------------------------+-------------------+-----------------------------------+------+-----+----------+-------------+----------+

### Proportional

+--------------------------+-------------------+-------------+------+-----+-----------+-------------+---------+
| benchmark                | subject           | set         | revs | its | mem_peak  | mode        | rstdev  |
+--------------------------+-------------------+-------------+------+-----+-----------+-------------+---------+
| MethodsProportionalBench | benchByCandidates | STV,3       | 1    | 3   | 5.829mb   | 0.002659s   | ±7.56%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,3   | 1    | 3   | 5.964mb   | 0.014000s   | ±73.36% |
| MethodsProportionalBench | benchByCandidates | STV,4       | 1    | 3   | 6.649mb   | 0.003441s   | ±0.53%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,4   | 1    | 3   | 6.770mb   | 0.018359s   | ±41.91% |
| MethodsProportionalBench | benchByCandidates | STV,5       | 1    | 3   | 7.470mb   | 0.004373s   | ±10.45% |
| MethodsProportionalBench | benchByCandidates | CPO STV,5   | 1    | 3   | 7.669mb   | 0.021245s   | ±31.55% |
| MethodsProportionalBench | benchByCandidates | STV,6       | 1    | 3   | 8.292mb   | 0.005903s   | ±11.56% |
| MethodsProportionalBench | benchByCandidates | CPO STV,6   | 1    | 3   | 10.407mb  | 0.166816s   | ±5.62%  |
| MethodsProportionalBench | benchByCandidates | STV,7       | 1    | 3   | 9.143mb   | 0.009377s   | ±19.86% |
| MethodsProportionalBench | benchByCandidates | CPO STV,7   | 1    | 3   | 13.332mb  | 0.328804s   | ±0.72%  |
| MethodsProportionalBench | benchByCandidates | STV,8       | 1    | 3   | 10.573mb  | 0.007267s   | ±3.74%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,8   | 1    | 3   | 18.034mb  | 0.611943s   | ±1.64%  |
| MethodsProportionalBench | benchByCandidates | STV,9       | 1    | 3   | 11.409mb  | 0.007971s   | ±17.82% |
| MethodsProportionalBench | benchByCandidates | CPO STV,9   | 1    | 3   | 83.441mb  | 5.551505s   | ±1.05%  |
| MethodsProportionalBench | benchByCandidates | STV,10      | 1    | 3   | 12.233mb  | 0.008690s   | ±3.30%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,10  | 1    | 3   | 155.667mb | 11.802237s  | ±0.17%  |
| MethodsProportionalBench | benchByCandidates | STV,11      | 1    | 3   | 13.056mb  | 0.009754s   | ±1.28%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,11  | 1    | 3   | 286.714mb | 23.326424s  | ±0.37%  |
| MethodsProportionalBench | benchByCandidates | STV,12      | 1    | 3   | 13.878mb  | 0.010488s   | ±4.76%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,12  | 1    | 3   | 2.417gb   | 287.180966s | ±13.06% |
| MethodsProportionalBench | benchByCandidates | STV,13      | 1    | 3   | 14.700mb  | 0.015853s   | ±1.94%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,13  | 1    | 3   | 14.666mb  | 0.004435s   | ±6.87%  |
| MethodsProportionalBench | benchByCandidates | STV,14      | 1    | 3   | 15.524mb  | 0.016821s   | ±2.48%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,14  | 1    | 3   | 15.487mb  | 0.005004s   | ±11.10% |
| MethodsProportionalBench | benchByCandidates | STV,15      | 1    | 3   | 16.345mb  | 0.016804s   | ±5.20%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,15  | 1    | 3   | 16.309mb  | 0.005149s   | ±10.15% |
| MethodsProportionalBench | benchByCandidates | STV,16      | 1    | 3   | 18.447mb  | 0.017804s   | ±8.62%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,16  | 1    | 3   | 18.411mb  | 0.005673s   | ±11.23% |
| MethodsProportionalBench | benchByCandidates | STV,17      | 1    | 3   | 19.327mb  | 0.018709s   | ±2.85%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,17  | 1    | 3   | 19.287mb  | 0.005789s   | ±6.55%  |
| MethodsProportionalBench | benchByCandidates | STV,18      | 1    | 3   | 20.157mb  | 0.018876s   | ±2.92%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,18  | 1    | 3   | 20.115mb  | 0.006241s   | ±3.11%  |
| MethodsProportionalBench | benchByCandidates | STV,19      | 1    | 3   | 20.982mb  | 0.022289s   | ±4.29%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,19  | 1    | 3   | 20.940mb  | 0.005274s   | ±8.26%  |
| MethodsProportionalBench | benchByCandidates | STV,20      | 1    | 3   | 21.809mb  | 0.022712s   | ±1.60%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,20  | 1    | 3   | 21.765mb  | 0.005522s   | ±8.89%  |
| MethodsProportionalBench | benchByCandidates | STV,30      | 1    | 3   | 30.112mb  | 0.032666s   | ±4.21%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,30  | 1    | 3   | 30.054mb  | 0.008870s   | ±6.10%  |
| MethodsProportionalBench | benchByCandidates | STV,40      | 1    | 3   | 41.229mb  | 0.044671s   | ±1.33%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,40  | 1    | 3   | 41.131mb  | 0.011938s   | ±6.82%  |
| MethodsProportionalBench | benchByCandidates | STV,50      | 1    | 3   | 49.601mb  | 0.057759s   | ±2.21%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,50  | 1    | 3   | 49.443mb  | 0.013495s   | ±6.86%  |
| MethodsProportionalBench | benchByCandidates | STV,60      | 1    | 3   | 57.954mb  | 0.068025s   | ±0.86%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,60  | 1    | 3   | 57.755mb  | 0.018584s   | ±4.75%  |
| MethodsProportionalBench | benchByCandidates | STV,70      | 1    | 3   | 79.813mb  | 0.083113s   | ±0.45%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,70  | 1    | 3   | 79.335mb  | 0.022136s   | ±3.20%  |
| MethodsProportionalBench | benchByCandidates | STV,80      | 1    | 3   | 88.545mb  | 0.096366s   | ±1.30%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,80  | 1    | 3   | 87.929mb  | 0.026070s   | ±2.65%  |
| MethodsProportionalBench | benchByCandidates | STV,90      | 1    | 3   | 97.297mb  | 0.111921s   | ±0.16%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,90  | 1    | 3   | 96.523mb  | 0.031376s   | ±2.81%  |
| MethodsProportionalBench | benchByCandidates | STV,100     | 1    | 3   | 106.003mb | 0.127644s   | ±1.99%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,100 | 1    | 3   | 105.116mb | 0.035404s   | ±1.94%  |
+--------------------------+-------------------+-------------+------+-----+-----------+-------------+---------+

# v4.0-alpha1 Branch

1000 random votes different for each test, variable number of candidates (look at column "set") 

* AMD Ryzen 9 5900X

PHPBench (1.2.5) 
with PHP version 8.1.6, xdebug ❌, opcache ✔ (with JIT Tracing)

```php
    #[Bench\Warmup(1)]
    #[Bench\Iterations(10)]
    #[Bench\Revs(1)]
```
### Non-Proportional


Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+-----------------------------+-------------------+-----------------------------------+------+-----+-----------+------------+----------+
| benchmark                   | subject           | set                               | revs | its | mem_peak  | mode       | rstdev   |
+-----------------------------+-------------------+-----------------------------------+------+-----+-----------+------------+----------+
| MethodsNonProportionalBench | benchByCandidates | BordaCount,3                      | 1    | 10  | 4.267mb   | 0.001576s  | ±28.18%  |
| MethodsNonProportionalBench | benchByCandidates | Copeland,3                        | 1    | 10  | 3.934mb   | 0.000024s  | ±11.86%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,3                   | 1    | 10  | 3.861mb   | 0.000020s  | ±5.84%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,3   | 1    | 10  | 3.863mb   | 0.000024s  | ±3.35%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,3                   | 1    | 10  | 3.925mb   | 0.001621s  | ±12.99%  |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,3                  | 1    | 10  | 3.931mb   | 0.003948s  | ±43.00%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,3                    | 1    | 10  | 3.937mb   | 0.000084s  | ±177.58% |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,3      | 1    | 10  | 3.928mb   | 0.001411s  | ±3.14%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,3          | 1    | 10  | 3.931mb   | 0.003039s  | ±17.49%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,3                 | 1    | 10  | 3.863mb   | 0.000024s  | ±3.95%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,3                  | 1    | 10  | 3.863mb   | 0.000023s  | ±3.92%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,3              | 1    | 10  | 3.863mb   | 0.000025s  | ±2.72%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,3             | 1    | 10  | 3.932mb   | 0.000036s  | ±186.95% |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,3            | 1    | 10  | 3.868mb   | 0.000037s  | ±8.30%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,3                 | 1    | 10  | 3.863mb   | 0.000028s  | ±3.09%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,3                  | 1    | 10  | 3.863mb   | 0.000030s  | ±24.61%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,3                   | 1    | 10  | 3.863mb   | 0.000030s  | ±1.54%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,5                      | 1    | 10  | 4.814mb   | 0.002172s  | ±18.53%  |
| MethodsNonProportionalBench | benchByCandidates | Copeland,5                        | 1    | 10  | 4.754mb   | 0.000030s  | ±6.13%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,5                   | 1    | 10  | 4.814mb   | 0.000025s  | ±190.78% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,5   | 1    | 10  | 4.754mb   | 0.000030s  | ±16.08%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,5                   | 1    | 10  | 4.784mb   | 0.002020s  | ±2.71%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,5                  | 1    | 10  | 4.818mb   | 0.008122s  | ±30.49%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,5                    | 1    | 10  | 4.946mb   | 0.000265s  | ±2.46%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,5      | 1    | 10  | 4.815mb   | 0.001705s  | ±45.21%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,5          | 1    | 10  | 4.816mb   | 0.003976s  | ±9.82%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,5                 | 1    | 10  | 4.754mb   | 0.000032s  | ±3.82%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,5                  | 1    | 10  | 4.754mb   | 0.000030s  | ±3.82%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,5              | 1    | 10  | 4.754mb   | 0.000030s  | ±2.54%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,5             | 1    | 10  | 4.836mb   | 0.000607s  | ±200.45% |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,5            | 1    | 10  | 4.774mb   | 0.000071s  | ±4.95%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,5                 | 1    | 10  | 4.824mb   | 0.000788s  | ±133.97% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,5                  | 1    | 10  | 4.754mb   | 0.000043s  | ±22.29%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,5                   | 1    | 10  | 4.817mb   | 0.002600s  | ±107.48% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,6                      | 1    | 10  | 5.228mb   | 0.002455s  | ±3.85%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,6                        | 1    | 10  | 5.267mb   | 0.000087s  | ±126.33% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,6                   | 1    | 10  | 5.197mb   | 0.000026s  | ±7.69%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,6   | 1    | 10  | 5.200mb   | 0.000030s  | ±3.57%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,6                   | 1    | 10  | 5.228mb   | 0.002404s  | ±3.31%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,6                  | 1    | 10  | 5.297mb   | 0.010311s  | ±0.64%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,6                    | 1    | 10  | 5.981mb   | 0.001599s  | ±14.74%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,6      | 1    | 10  | 5.228mb   | 0.001897s  | ±2.98%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,6          | 1    | 10  | 5.229mb   | 0.004465s  | ±2.30%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,6                 | 1    | 10  | 5.200mb   | 0.000030s  | ±6.99%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,6                  | 1    | 10  | 5.200mb   | 0.000030s  | ±7.99%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,6              | 1    | 10  | 5.200mb   | 0.000031s  | ±5.87%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,6             | 1    | 10  | 5.292mb   | 0.000143s  | ±194.55% |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,6            | 1    | 10  | 5.229mb   | 0.000097s  | ±10.28%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,6                 | 1    | 10  | 5.267mb   | 0.000367s  | ±164.46% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,6                  | 1    | 10  | 5.262mb   | 0.000041s  | ±258.69% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,6                   | 1    | 10  | 5.259mb   | 0.001026s  | ±120.33% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,7                      | 1    | 10  | 5.672mb   | 0.002705s  | ±4.59%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,7                        | 1    | 10  | 5.708mb   | 0.000048s  | ±95.75%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,7                   | 1    | 10  | 5.712mb   | 0.000031s  | ±246.98% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,7   | 1    | 10  | 5.705mb   | 0.000032s  | ±180.86% |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,7                   | 1    | 10  | 5.673mb   | 0.002667s  | ±2.17%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,7                  | 1    | 10  | 5.712mb   | 0.013131s  | ±4.15%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,7                    | 1    | 10  | 11.331mb  | 0.013923s  | ±5.26%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,7      | 1    | 10  | 5.673mb   | 0.002031s  | ±2.42%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,7          | 1    | 10  | 5.673mb   | 0.004654s  | ±1.60%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,7                 | 1    | 10  | 5.704mb   | 0.000036s  | ±142.26% |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,7                  | 1    | 10  | 5.645mb   | 0.000032s  | ±3.34%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,7              | 1    | 10  | 5.645mb   | 0.000033s  | ±4.84%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,7             | 1    | 10  | 5.749mb   | 0.000365s  | ±128.92% |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,7            | 1    | 10  | 5.688mb   | 0.000138s  | ±9.68%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,7                 | 1    | 10  | 5.703mb   | 0.000051s  | ±169.59% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,7                  | 1    | 10  | 5.703mb   | 0.000052s  | ±219.33% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,7                   | 1    | 10  | 5.703mb   | 0.000408s  | ±146.29% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,8                      | 1    | 10  | 6.437mb   | 0.002997s  | ±12.75%  |
| MethodsNonProportionalBench | benchByCandidates | Copeland,8                        | 1    | 10  | 6.412mb   | 0.000034s  | ±4.63%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,8                   | 1    | 10  | 6.468mb   | 0.000031s  | ±144.07% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,8   | 1    | 10  | 6.412mb   | 0.000034s  | ±4.84%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,8                   | 1    | 10  | 6.437mb   | 0.002964s  | ±2.69%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,8                  | 1    | 10  | 6.475mb   | 0.016236s  | ±6.00%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,8                    | 1    | 10  | 83.245mb  | 0.150337s  | ±1.54%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,8      | 1    | 10  | 6.475mb   | 0.002121s  | ±19.90%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,8          | 1    | 10  | 6.437mb   | 0.005121s  | ±12.26%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,8                 | 1    | 10  | 6.412mb   | 0.000034s  | ±12.26%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,8                  | 1    | 10  | 6.412mb   | 0.000036s  | ±4.23%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,8              | 1    | 10  | 6.412mb   | 0.000036s  | ±4.70%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,8             | 1    | 10  | 6.467mb   | 0.000255s  | ±11.85%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,8            | 1    | 10  | 6.468mb   | 0.000245s  | ±9.11%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,8                 | 1    | 10  | 6.466mb   | 0.000063s  | ±207.41% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,8                  | 1    | 10  | 6.412mb   | 0.000065s  | ±3.48%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,8                   | 1    | 10  | 6.467mb   | 0.001233s  | ±129.54% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,9                      | 1    | 10  | 6.896mb   | 0.003210s  | ±2.84%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,9                        | 1    | 10  | 6.926mb   | 0.000036s  | ±238.83% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,9                   | 1    | 10  | 6.870mb   | 0.000030s  | ±5.64%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,9   | 1    | 10  | 6.872mb   | 0.000036s  | ±1.86%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,9                   | 1    | 10  | 6.896mb   | 0.003026s  | ±2.54%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,9                  | 1    | 10  | 6.926mb   | 0.019699s  | ±2.99%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,9                    | 1    | 10  | 672.151mb | 1.652125s  | ±0.27%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,9      | 1    | 10  | 6.895mb   | 0.002209s  | ±3.77%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,9          | 1    | 10  | 6.895mb   | 0.005537s  | ±1.11%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,9                 | 1    | 10  | 6.872mb   | 0.000037s  | ±14.11%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,9                  | 1    | 10  | 6.872mb   | 0.000036s  | ±4.33%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,9              | 1    | 10  | 6.872mb   | 0.000036s  | ±5.63%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,9             | 1    | 10  | 6.952mb   | 0.000362s  | ±91.18%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,9            | 1    | 10  | 6.938mb   | 0.000309s  | ±14.39%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,9                 | 1    | 10  | 6.931mb   | 0.000078s  | ±157.32% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,9                  | 1    | 10  | 6.875mb   | 0.000081s  | ±26.92%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,9                   | 1    | 10  | 6.928mb   | 0.000917s  | ±105.36% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,10                     | 1    | 10  | 7.343mb   | 0.003537s  | ±3.00%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,10                       | 1    | 10  | 7.385mb   | 0.000046s  | ±194.34% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,10                  | 1    | 10  | 7.319mb   | 0.000034s  | ±4.46%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,10  | 1    | 10  | 7.321mb   | 0.000038s  | ±11.93%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,10                  | 1    | 10  | 7.343mb   | 0.003360s  | ±8.59%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,10                 | 1    | 10  | 7.350mb   | 0.023538s  | ±0.45%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,10                   | 1    | 10  | 7.368mb   | 0.000007s  | ±15.07%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,10     | 1    | 10  | 7.343mb   | 0.002383s  | ±3.89%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,10         | 1    | 10  | 7.343mb   | 0.006007s  | ±1.82%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,10                | 1    | 10  | 7.321mb   | 0.000041s  | ±38.78%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,10                 | 1    | 10  | 7.321mb   | 0.000041s  | ±3.66%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,10             | 1    | 10  | 7.321mb   | 0.000039s  | ±3.33%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,10            | 1    | 10  | 7.403mb   | 0.000503s  | ±12.11%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,10           | 1    | 10  | 7.403mb   | 0.000520s  | ±14.33%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,10                | 1    | 10  | 7.328mb   | 0.000089s  | ±1.48%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,10                 | 1    | 10  | 7.328mb   | 0.000094s  | ±1.89%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,10                  | 1    | 10  | 7.375mb   | 0.001436s  | ±114.24% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,20                     | 1    | 10  | 12.566mb  | 0.006712s  | ±12.42%  |
| MethodsNonProportionalBench | benchByCandidates | Copeland,20                       | 1    | 10  | 12.563mb  | 0.000081s  | ±139.57% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,20                  | 1    | 10  | 12.507mb  | 0.000054s  | ±188.52% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,20  | 1    | 10  | 12.536mb  | 0.000067s  | ±205.54% |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,20                  | 1    | 10  | 12.510mb  | 0.006443s  | ±1.21%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,20                 | 1    | 10  | 12.554mb  | 0.080874s  | ±2.86%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,20                   | 1    | 10  | 12.473mb  | 0.000007s  | ±13.61%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,20     | 1    | 10  | 12.509mb  | 0.004153s  | ±2.27%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,20         | 1    | 10  | 12.509mb  | 0.010935s  | ±2.79%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,20                | 1    | 10  | 12.502mb  | 0.000066s  | ±9.52%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,20                 | 1    | 10  | 12.502mb  | 0.000066s  | ±2.94%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,20             | 1    | 10  | 12.502mb  | 0.000066s  | ±2.51%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,20            | 1    | 10  | 12.798mb  | 0.012576s  | ±10.90%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,20           | 1    | 10  | 12.804mb  | 0.012314s  | ±11.26%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,20                | 1    | 10  | 12.563mb  | 0.000429s  | ±76.02%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,20                 | 1    | 10  | 12.541mb  | 0.000446s  | ±2.50%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,20                  | 1    | 10  | 12.558mb  | 0.005883s  | ±89.26%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,30                     | 1    | 10  | 17.042mb  | 0.010397s  | ±3.89%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,30                       | 1    | 10  | 17.046mb  | 0.000104s  | ±13.62%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,30                  | 1    | 10  | 17.053mb  | 0.000073s  | ±5.49%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,30  | 1    | 10  | 17.047mb  | 0.000107s  | ±4.60%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,30                  | 1    | 10  | 17.042mb  | 0.010148s  | ±3.27%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,30                 | 1    | 10  | 17.084mb  | 0.181320s  | ±9.11%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,30                   | 1    | 10  | 17.006mb  | 0.000009s  | ±4.55%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,30     | 1    | 10  | 17.042mb  | 0.006459s  | ±4.71%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,30         | 1    | 10  | 17.077mb  | 0.017216s  | ±5.29%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,30                | 1    | 10  | 17.046mb  | 0.000107s  | ±2.39%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,30                 | 1    | 10  | 17.044mb  | 0.000105s  | ±3.36%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,30             | 1    | 10  | 17.046mb  | 0.000106s  | ±4.30%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,30            | 1    | 10  | 17.674mb  | 0.095572s  | ±6.65%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,30           | 1    | 10  | 17.691mb  | 0.102611s  | ±13.10%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,30                | 1    | 10  | 17.105mb  | 0.001341s  | ±36.46%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,30                 | 1    | 10  | 17.104mb  | 0.001306s  | ±3.18%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,30                  | 1    | 10  | 17.104mb  | 0.006312s  | ±51.42%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,40                     | 1    | 10  | 23.131mb  | 0.014433s  | ±9.34%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,40                       | 1    | 10  | 23.132mb  | 0.000156s  | ±218.86% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,40                  | 1    | 10  | 23.173mb  | 0.000108s  | ±172.81% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,40  | 1    | 10  | 23.099mb  | 0.000152s  | ±4.32%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,40                  | 1    | 10  | 23.086mb  | 0.013914s  | ±5.24%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,40                 | 1    | 10  | 23.181mb  | 0.319985s  | ±5.15%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,40                   | 1    | 10  | 23.044mb  | 0.000007s  | ±14.02%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,40     | 1    | 10  | 23.093mb  | 0.008670s  | ±5.04%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,40         | 1    | 10  | 23.095mb  | 0.023546s  | ±2.91%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,40                | 1    | 10  | 23.090mb  | 0.000151s  | ±2.76%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,40                 | 1    | 10  | 23.089mb  | 0.000148s  | ±11.23%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,40             | 1    | 10  | 23.157mb  | 0.000149s  | ±142.96% |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,40            | 1    | 10  | 24.233mb  | 0.428470s  | ±12.23%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,40           | 1    | 10  | 24.192mb  | 0.386814s  | ±7.69%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,40                | 1    | 10  | 23.277mb  | 0.002867s  | ±2.14%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,40                 | 1    | 10  | 23.269mb  | 0.002939s  | ±1.61%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,40                  | 1    | 10  | 23.276mb  | 0.006179s  | ±47.41%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,50                     | 1    | 10  | 27.650mb  | 0.018801s  | ±4.38%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,50                       | 1    | 10  | 27.663mb  | 0.000208s  | ±11.64%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,50                  | 1    | 10  | 27.723mb  | 0.000131s  | ±12.28%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,50  | 1    | 10  | 27.667mb  | 0.000210s  | ±2.78%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,50                  | 1    | 10  | 27.650mb  | 0.018225s  | ±2.90%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,50                 | 1    | 10  | 27.787mb  | 0.485798s  | ±5.60%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,50                   | 1    | 10  | 27.600mb  | 0.000009s  | ±11.13%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,50     | 1    | 10  | 27.630mb  | 0.011091s  | ±0.92%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,50         | 1    | 10  | 27.630mb  | 0.030712s  | ±0.76%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,50                | 1    | 10  | 27.652mb  | 0.000206s  | ±4.21%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,50                 | 1    | 10  | 27.659mb  | 0.000204s  | ±13.61%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,50             | 1    | 10  | 27.652mb  | 0.000207s  | ±5.47%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,50            | 1    | 10  | 29.310mb  | 1.206939s  | ±10.50%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,50           | 1    | 10  | 29.390mb  | 1.320606s  | ±14.78%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,50                | 1    | 10  | 27.886mb  | 0.005423s  | ±3.75%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,50                 | 1    | 10  | 27.885mb  | 0.005669s  | ±2.27%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,50                  | 1    | 10  | 27.887mb  | 0.011125s  | ±3.40%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,60                     | 1    | 10  | 32.214mb  | 0.023670s  | ±0.80%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,60                       | 1    | 10  | 32.231mb  | 0.000278s  | ±2.58%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,60                  | 1    | 10  | 32.300mb  | 0.000165s  | ±3.40%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,60  | 1    | 10  | 32.234mb  | 0.000280s  | ±1.53%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,60                  | 1    | 10  | 32.214mb  | 0.022865s  | ±0.68%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,60                 | 1    | 10  | 32.376mb  | 0.686883s  | ±6.15%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,60                   | 1    | 10  | 32.156mb  | 0.000008s  | ±13.22%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,60     | 1    | 10  | 32.185mb  | 0.013784s  | ±1.14%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,60         | 1    | 10  | 32.201mb  | 0.038390s  | ±3.17%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,60                | 1    | 10  | 32.222mb  | 0.000281s  | ±10.97%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,60                 | 1    | 10  | 32.223mb  | 0.000271s  | ±2.46%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,60             | 1    | 10  | 32.220mb  | 0.000277s  | ±12.89%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,60            | 1    | 10  | 34.723mb  | 3.552521s  | ±13.97%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,60           | 1    | 10  | 34.738mb  | 3.476462s  | ±14.63%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,60                | 1    | 10  | 32.496mb  | 0.009344s  | ±1.57%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,60                 | 1    | 10  | 32.498mb  | 0.009387s  | ±3.33%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,60                  | 1    | 10  | 32.495mb  | 0.019263s  | ±6.10%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,70                     | 1    | 10  | 44.438mb  | 0.029599s  | ±3.34%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,70                       | 1    | 10  | 44.433mb  | 0.000359s  | ±12.38%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,70                  | 1    | 10  | 44.726mb  | 0.000208s  | ±3.93%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,70  | 1    | 10  | 44.461mb  | 0.000365s  | ±11.99%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,70                  | 1    | 10  | 44.438mb  | 0.028667s  | ±0.84%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,70                 | 1    | 10  | 44.794mb  | 0.997542s  | ±6.81%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,70                   | 1    | 10  | 44.360mb  | 0.000008s  | ±14.31%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,70     | 1    | 10  | 44.391mb  | 0.017181s  | ±0.65%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,70         | 1    | 10  | 44.391mb  | 0.048083s  | ±0.67%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,70                | 1    | 10  | 44.420mb  | 0.000351s  | ±12.13%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,70                 | 1    | 10  | 44.421mb  | 0.000351s  | ±5.41%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,70             | 1    | 10  | 44.420mb  | 0.000351s  | ±3.01%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,70            | 1    | 10  | 47.741mb  | 8.258612s  | ±11.24%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,70           | 1    | 10  | 47.702mb  | 7.574171s  | ±14.00%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,70                | 1    | 10  | 45.529mb  | 0.015059s  | ±1.48%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,70                 | 1    | 10  | 45.533mb  | 0.015142s  | ±2.79%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,70                  | 1    | 10  | 45.531mb  | 0.030803s  | ±7.93%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,80                     | 1    | 10  | 49.284mb  | 0.035633s  | ±7.12%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,80                       | 1    | 10  | 49.304mb  | 0.000453s  | ±0.95%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,80                  | 1    | 10  | 49.635mb  | 0.000254s  | ±12.19%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,80  | 1    | 10  | 49.310mb  | 0.000472s  | ±3.59%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,80                  | 1    | 10  | 49.284mb  | 0.035766s  | ±1.52%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,80                 | 1    | 10  | 49.724mb  | 1.294201s  | ±7.97%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,80                   | 1    | 10  | 49.197mb  | 0.000007s  | ±13.42%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,80     | 1    | 10  | 49.232mb  | 0.020609s  | ±1.12%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,80         | 1    | 10  | 49.232mb  | 0.058361s  | ±3.16%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,80                | 1    | 10  | 49.266mb  | 0.000440s  | ±1.60%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,80                 | 1    | 10  | 49.266mb  | 0.000444s  | ±4.79%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,80             | 1    | 10  | 49.266mb  | 0.000443s  | ±10.14%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,80            | 1    | 10  | 53.461mb  | 16.095014s | ±15.87%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,80           | 1    | 10  | 53.590mb  | 17.480178s | ±15.05%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,80                | 1    | 10  | 50.531mb  | 0.022074s  | ±1.95%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,80                 | 1    | 10  | 50.534mb  | 0.022177s  | ±2.54%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,80                  | 1    | 10  | 50.533mb  | 0.045875s  | ±1.76%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,90                     | 1    | 10  | 54.129mb  | 0.042823s  | ±0.75%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,90                       | 1    | 10  | 54.151mb  | 0.000553s  | ±6.90%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,90                  | 1    | 10  | 54.500mb  | 0.000302s  | ±2.45%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,90  | 1    | 10  | 54.159mb  | 0.000565s  | ±2.31%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,90                  | 1    | 10  | 54.129mb  | 0.041830s  | ±1.53%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,90                 | 1    | 10  | 54.518mb  | 1.524077s  | ±9.39%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,90                   | 1    | 10  | 54.035mb  | 0.000009s  | ±15.11%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,90     | 1    | 10  | 54.073mb  | 0.024268s  | ±3.10%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,90         | 1    | 10  | 54.073mb  | 0.068611s  | ±0.86%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,90                | 1    | 10  | 54.110mb  | 0.000534s  | ±1.59%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,90                 | 1    | 10  | 54.108mb  | 0.000534s  | ±2.85%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,90             | 1    | 10  | 54.109mb  | 0.000536s  | ±1.37%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,90            | 1    | 10  | 59.332mb  | 28.202594s | ±14.13%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,90           | 1    | 10  | 59.359mb  | 30.213354s | ±13.33%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,90                | 1    | 10  | 55.538mb  | 0.030684s  | ±5.05%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,90                 | 1    | 10  | 55.536mb  | 0.031059s  | ±2.32%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,90                  | 1    | 10  | 55.535mb  | 0.065110s  | ±2.80%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,100                    | 1    | 10  | 58.975mb  | 0.049534s  | ±1.17%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,100                      | 1    | 10  | 59.000mb  | 0.000659s  | ±0.98%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,100                 | 1    | 10  | 59.411mb  | 0.000359s  | ±1.42%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,100 | 1    | 10  | 59.008mb  | 0.000672s  | ±2.88%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,100                 | 1    | 10  | 58.975mb  | 0.048570s  | ±0.47%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,100                | 1    | 10  | 59.498mb  | 1.692495s  | ±8.43%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,100                  | 1    | 10  | 58.872mb  | 0.000007s  | ±14.79%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,100    | 1    | 10  | 58.914mb  | 0.027877s  | ±1.41%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,100        | 1    | 10  | 58.914mb  | 0.078369s  | ±4.47%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,100               | 1    | 10  | 58.950mb  | 0.000642s  | ±25.20%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,100                | 1    | 10  | 58.954mb  | 0.000644s  | ±4.31%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,100            | 1    | 10  | 58.951mb  | 0.000633s  | ±3.12%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,100           | 1    | 10  | 65.485mb  | 51.116404s | ±11.11%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,100          | 1    | 10  | 65.473mb  | 46.500300s | ±12.30%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,100               | 1    | 10  | 60.536mb  | 0.041783s  | ±4.37%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,100                | 1    | 10  | 60.541mb  | 0.043380s  | ±1.90%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,100                 | 1    | 10  | 60.537mb  | 0.089583s  | ±5.19%   |
+-----------------------------+-------------------+-----------------------------------+------+-----+-----------+------------+----------+

### Proportional

Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+--------------------------+-------------------+-------------+------+-----+-----------+-------------+---------+
| benchmark                | subject           | set         | revs | its | mem_peak  | mode        | rstdev  |
+--------------------------+-------------------+-------------+------+-----+-----------+-------------+---------+
| MethodsProportionalBench | benchByCandidates | STV,3       | 1    | 10  | 5.827mb   | 0.002783s   | ±7.72%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,3   | 1    | 10  | 5.950mb   | 0.006918s   | ±90.37% |
| MethodsProportionalBench | benchByCandidates | STV,4       | 1    | 10  | 6.610mb   | 0.003713s   | ±8.19%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,4   | 1    | 10  | 6.754mb   | 0.011379s   | ±20.24% |
| MethodsProportionalBench | benchByCandidates | STV,5       | 1    | 10  | 7.402mb   | 0.004458s   | ±2.57%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,5   | 1    | 10  | 7.678mb   | 0.019062s   | ±19.13% |
| MethodsProportionalBench | benchByCandidates | STV,6       | 1    | 10  | 8.224mb   | 0.004669s   | ±8.40%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,6   | 1    | 10  | 10.395mb  | 0.160481s   | ±1.31%  |
| MethodsProportionalBench | benchByCandidates | STV,7       | 1    | 10  | 9.045mb   | 0.005661s   | ±18.25% |
| MethodsProportionalBench | benchByCandidates | CPO STV,7   | 1    | 10  | 13.321mb  | 0.332263s   | ±1.08%  |
| MethodsProportionalBench | benchByCandidates | STV,8       | 1    | 10  | 10.505mb  | 0.007117s   | ±9.94%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,8   | 1    | 10  | 18.022mb  | 0.614972s   | ±0.90%  |
| MethodsProportionalBench | benchByCandidates | STV,9       | 1    | 10  | 11.342mb  | 0.007548s   | ±4.08%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,9   | 1    | 10  | 84.893mb  | 5.877915s   | ±0.57%  |
| MethodsProportionalBench | benchByCandidates | STV,10      | 1    | 10  | 12.165mb  | 0.008954s   | ±13.21% |
| MethodsProportionalBench | benchByCandidates | CPO STV,10  | 1    | 10  | 155.658mb | 12.608246s  | ±0.61%  |
| MethodsProportionalBench | benchByCandidates | STV,11      | 1    | 10  | 12.988mb  | 0.009892s   | ±12.51% |
| MethodsProportionalBench | benchByCandidates | CPO STV,11  | 1    | 10  | 290.940mb | 25.131801s  | ±0.51%  |
| MethodsProportionalBench | benchByCandidates | STV,12      | 1    | 10  | 13.811mb  | 0.009862s   | ±4.28%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,12  | 1    | 10  | 2.417gb   | 288.783020s | ±1.93%  |
| MethodsProportionalBench | benchByCandidates | STV,13      | 1    | 10  | 14.632mb  | 0.010369s   | ±1.90%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,13  | 1    | 10  | 8.642mb   | 0.000008s   | ±9.13%  |
| MethodsProportionalBench | benchByCandidates | STV,14      | 1    | 10  | 15.455mb  | 0.011096s   | ±15.92% |
| MethodsProportionalBench | benchByCandidates | CPO STV,14  | 1    | 10  | 9.088mb   | 0.000008s   | ±6.82%  |
| MethodsProportionalBench | benchByCandidates | STV,15      | 1    | 10  | 16.278mb  | 0.011425s   | ±1.81%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,15  | 1    | 10  | 9.534mb   | 0.000008s   | ±11.18% |
| MethodsProportionalBench | benchByCandidates | STV,16      | 1    | 10  | 18.380mb  | 0.012343s   | ±2.40%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,16  | 1    | 10  | 10.621mb  | 0.000009s   | ±10.91% |
| MethodsProportionalBench | benchByCandidates | STV,17      | 1    | 10  | 19.260mb  | 0.013621s   | ±7.09%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,17  | 1    | 10  | 11.123mb  | 0.000008s   | ±7.91%  |
| MethodsProportionalBench | benchByCandidates | STV,18      | 1    | 10  | 20.088mb  | 0.013799s   | ±2.16%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,18  | 1    | 10  | 11.575mb  | 0.000007s   | ±11.94% |
| MethodsProportionalBench | benchByCandidates | STV,19      | 1    | 10  | 20.916mb  | 0.014612s   | ±0.75%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,19  | 1    | 10  | 12.024mb  | 0.000009s   | ±5.27%  |
| MethodsProportionalBench | benchByCandidates | STV,20      | 1    | 10  | 21.743mb  | 0.015802s   | ±3.77%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,20  | 1    | 10  | 12.473mb  | 0.000008s   | ±10.84% |
| MethodsProportionalBench | benchByCandidates | STV,30      | 1    | 10  | 30.044mb  | 0.023349s   | ±3.99%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,30  | 1    | 10  | 17.006mb  | 0.000009s   | ±10.26% |
| MethodsProportionalBench | benchByCandidates | STV,40      | 1    | 10  | 41.181mb  | 0.032300s   | ±4.33%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,40  | 1    | 10  | 23.044mb  | 0.000009s   | ±10.37% |
| MethodsProportionalBench | benchByCandidates | STV,50      | 1    | 10  | 49.535mb  | 0.040636s   | ±4.98%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,50  | 1    | 10  | 27.600mb  | 0.000008s   | ±7.71%  |
| MethodsProportionalBench | benchByCandidates | STV,60      | 1    | 10  | 57.888mb  | 0.051150s   | ±2.49%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,60  | 1    | 10  | 32.156mb  | 0.000009s   | ±10.91% |
| MethodsProportionalBench | benchByCandidates | STV,70      | 1    | 10  | 79.741mb  | 0.062366s   | ±2.51%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,70  | 1    | 10  | 44.360mb  | 0.000009s   | ±13.15% |
| MethodsProportionalBench | benchByCandidates | STV,80      | 1    | 10  | 88.468mb  | 0.074088s   | ±3.38%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,80  | 1    | 10  | 49.197mb  | 0.000008s   | ±8.98%  |
| MethodsProportionalBench | benchByCandidates | STV,90      | 1    | 10  | 97.212mb  | 0.083958s   | ±4.51%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,90  | 1    | 10  | 54.035mb  | 0.000010s   | ±12.74% |
| MethodsProportionalBench | benchByCandidates | STV,100     | 1    | 10  | 105.939mb | 0.097074s   | ±3.65%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,100 | 1    | 10  | 58.872mb  | 0.000009s   | ±16.69% |
+--------------------------+-------------------+-------------+------+-----+-----------+-------------+---------+


# v3.3.3

1000 random votes different for each test, variable number of candidates (look at column "set") 

* AMD Ryzen 9 5900X

PHPBench (1.2.5) 
with PHP version 8.1.6, xdebug ❌, opcache ✔ (with JIT Tracing)

```php
    #[Bench\Warmup(1)]
    #[Bench\Iterations(3)]
    #[Bench\Revs(1)]
```

### Non-Proportional

Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+-----------------------------+-------------------+-----------------------------------+------+-----+-----------+------------+----------+
| benchmark                   | subject           | set                               | revs | its | mem_peak  | mode       | rstdev   |
+-----------------------------+-------------------+-----------------------------------+------+-----+-----------+------------+----------+
| MethodsNonProportionalBench | benchByCandidates | BordaCount,3                      | 1    | 3   | 4.155mb   | 0.001963s  | ±26.64%  |
| MethodsNonProportionalBench | benchByCandidates | Copeland,3                        | 1    | 3   | 3.821mb   | 0.000021s  | ±5.85%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,3                   | 1    | 3   | 3.749mb   | 0.000019s  | ±2.44%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,3   | 1    | 3   | 3.750mb   | 0.000022s  | ±5.58%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,3                   | 1    | 3   | 3.811mb   | 0.001702s  | ±9.12%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,3                  | 1    | 3   | 3.819mb   | 0.005508s  | ±50.37%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,3                    | 1    | 3   | 3.815mb   | 0.001621s  | ±75.46%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,3      | 1    | 3   | 3.820mb   | 0.001854s  | ±10.30%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,3          | 1    | 3   | 3.818mb   | 0.003639s  | ±31.01%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,3                 | 1    | 3   | 3.750mb   | 0.000024s  | ±5.27%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,3                  | 1    | 3   | 3.750mb   | 0.000024s  | ±4.04%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,3              | 1    | 3   | 3.750mb   | 0.000022s  | ±4.16%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,3             | 1    | 3   | 3.819mb   | 0.001493s  | ±78.21%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,3            | 1    | 3   | 3.814mb   | 0.000117s  | ±131.05% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,3                 | 1    | 3   | 3.813mb   | 0.002645s  | ±1.45%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,3                  | 1    | 3   | 3.817mb   | 0.001402s  | ±3.32%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,3                   | 1    | 3   | 3.815mb   | 0.001413s  | ±1.82%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,5                      | 1    | 3   | 4.708mb   | 0.003679s  | ±20.48%  |
| MethodsNonProportionalBench | benchByCandidates | Copeland,5                        | 1    | 3   | 4.641mb   | 0.000029s  | ±1.64%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,5                   | 1    | 3   | 4.639mb   | 0.000025s  | ±3.87%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,5   | 1    | 3   | 4.641mb   | 0.000029s  | ±4.35%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,5                   | 1    | 3   | 4.671mb   | 0.002098s  | ±0.18%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,5                  | 1    | 3   | 4.702mb   | 0.009091s  | ±27.69%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,5                    | 1    | 3   | 4.840mb   | 0.000275s  | ±2.76%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,5      | 1    | 3   | 4.702mb   | 0.001902s  | ±49.63%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,5          | 1    | 3   | 4.672mb   | 0.004162s  | ±0.18%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,5                 | 1    | 3   | 4.641mb   | 0.000061s  | ±30.28%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,5                  | 1    | 3   | 4.641mb   | 0.000030s  | ±4.11%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,5              | 1    | 3   | 4.641mb   | 0.000030s  | ±3.21%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,5             | 1    | 3   | 4.722mb   | 0.003208s  | ±101.35% |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,5            | 1    | 3   | 4.662mb   | 0.000069s  | ±7.68%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,5                 | 1    | 3   | 4.704mb   | 0.005819s  | ±49.15%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,5                  | 1    | 3   | 4.701mb   | 0.000128s  | ±129.71% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,5                   | 1    | 3   | 4.702mb   | 0.016854s  | ±43.28%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,6                      | 1    | 3   | 5.115mb   | 0.002719s  | ±4.32%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,6                        | 1    | 3   | 5.157mb   | 0.001274s  | ±88.64%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,6                   | 1    | 3   | 5.146mb   | 0.001564s  | ±78.43%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,6   | 1    | 3   | 5.145mb   | 0.000116s  | ±133.45% |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,6                   | 1    | 3   | 5.115mb   | 0.002493s  | ±3.71%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,6                  | 1    | 3   | 5.155mb   | 0.012630s  | ±14.09%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,6                    | 1    | 3   | 5.892mb   | 0.001688s  | ±1.13%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,6      | 1    | 3   | 5.115mb   | 0.001975s  | ±5.80%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,6          | 1    | 3   | 5.116mb   | 0.004745s  | ±1.49%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,6                 | 1    | 3   | 5.087mb   | 0.000030s  | ±3.21%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,6                  | 1    | 3   | 5.087mb   | 0.000030s  | ±1.59%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,6              | 1    | 3   | 5.146mb   | 0.000117s  | ±132.89% |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,6             | 1    | 3   | 5.183mb   | 0.002663s  | ±39.44%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,6            | 1    | 3   | 5.160mb   | 0.002587s  | ±67.19%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,6                 | 1    | 3   | 5.155mb   | 0.007516s  | ±26.07%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,6                  | 1    | 3   | 5.142mb   | 0.000046s  | ±8.09%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,6                   | 1    | 3   | 5.145mb   | 0.009898s  | ±28.00%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,7                      | 1    | 3   | 5.560mb   | 0.002982s  | ±6.98%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,7                        | 1    | 3   | 5.532mb   | 0.000030s  | ±1.55%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,7                   | 1    | 3   | 5.598mb   | 0.000195s  | ±137.37% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,7   | 1    | 3   | 5.594mb   | 0.002681s  | ±26.87%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,7                   | 1    | 3   | 5.560mb   | 0.002726s  | ±4.55%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,7                  | 1    | 3   | 5.597mb   | 0.015668s  | ±3.78%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,7                    | 1    | 3   | 11.403mb  | 0.014314s  | ±0.84%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,7      | 1    | 3   | 5.560mb   | 0.002040s  | ±0.84%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,7          | 1    | 3   | 5.560mb   | 0.005043s  | ±0.56%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,7                 | 1    | 3   | 5.594mb   | 0.000115s  | ±132.92% |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,7                  | 1    | 3   | 5.591mb   | 0.000115s  | ±132.92% |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,7              | 1    | 3   | 5.590mb   | 0.001316s  | ±68.39%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,7             | 1    | 3   | 5.613mb   | 0.001571s  | ±30.65%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,7            | 1    | 3   | 5.570mb   | 0.000148s  | ±7.12%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,7                 | 1    | 3   | 5.533mb   | 0.000051s  | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,7                  | 1    | 3   | 5.588mb   | 0.000137s  | ±126.51% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,7                   | 1    | 3   | 5.590mb   | 0.011652s  | ±9.33%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,8                      | 1    | 3   | 6.324mb   | 0.003245s  | ±2.60%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,8                        | 1    | 3   | 6.351mb   | 0.000121s  | ±131.83% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,8                   | 1    | 3   | 6.355mb   | 0.002881s  | ±36.70%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,8   | 1    | 3   | 6.299mb   | 0.000030s  | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,8                   | 1    | 3   | 6.324mb   | 0.002904s  | ±6.87%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,8                  | 1    | 3   | 6.360mb   | 0.019624s  | ±1.95%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,8                    | 1    | 3   | 86.609mb  | 0.161120s  | ±0.56%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,8      | 1    | 3   | 6.324mb   | 0.002280s  | ±0.32%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,8          | 1    | 3   | 6.324mb   | 0.005577s  | ±0.18%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,8                 | 1    | 3   | 6.299mb   | 0.000030s  | ±3.07%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,8                  | 1    | 3   | 6.299mb   | 0.000030s  | ±3.21%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,8              | 1    | 3   | 6.299mb   | 0.000030s  | ±3.07%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,8             | 1    | 3   | 6.348mb   | 0.000191s  | ±10.55%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,8            | 1    | 3   | 6.352mb   | 0.000243s  | ±7.51%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,8                 | 1    | 3   | 6.300mb   | 0.000063s  | ±2.93%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,8                  | 1    | 3   | 6.353mb   | 0.000152s  | ±122.80% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,8                   | 1    | 3   | 6.353mb   | 0.001166s  | ±87.98%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,9                      | 1    | 3   | 6.783mb   | 0.003559s  | ±2.27%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,9                        | 1    | 3   | 6.759mb   | 0.000034s  | ±4.80%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,9                   | 1    | 3   | 6.757mb   | 0.000029s  | ±9.12%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,9   | 1    | 3   | 6.759mb   | 0.000033s  | ±3.82%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,9                   | 1    | 3   | 6.783mb   | 0.003158s  | ±0.32%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,9                  | 1    | 3   | 6.813mb   | 0.023297s  | ±3.77%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,9                    | 1    | 3   | 684.621mb | 1.772223s  | ±0.04%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,9      | 1    | 3   | 6.783mb   | 0.002401s  | ±0.14%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,9          | 1    | 3   | 6.783mb   | 0.006061s  | ±1.52%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,9                 | 1    | 3   | 6.759mb   | 0.000036s  | ±4.04%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,9                  | 1    | 3   | 6.759mb   | 0.000036s  | ±7.03%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,9              | 1    | 3   | 6.759mb   | 0.000034s  | ±3.63%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,9             | 1    | 3   | 6.836mb   | 0.000386s  | ±88.55%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,9            | 1    | 3   | 6.828mb   | 0.000291s  | ±17.69%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,9                 | 1    | 3   | 6.762mb   | 0.000080s  | ±10.50%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,9                  | 1    | 3   | 6.762mb   | 0.000087s  | ±2.76%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,9                   | 1    | 3   | 6.815mb   | 0.003755s  | ±69.19%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,10                     | 1    | 3   | 7.230mb   | 0.003945s  | ±2.48%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,10                       | 1    | 3   | 7.208mb   | 0.000036s  | ±2.67%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,10                  | 1    | 3   | 7.206mb   | 0.000030s  | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,10  | 1    | 3   | 7.208mb   | 0.000036s  | ±2.27%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,10                  | 1    | 3   | 7.230mb   | 0.003460s  | ±1.93%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,10                 | 1    | 3   | 7.238mb   | 0.025816s  | ±0.86%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,10                   | 1    | 3   | 7.256mb   | 0.000006s  | ±35.79%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,10     | 1    | 3   | 7.230mb   | 0.002606s  | ±2.04%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,10         | 1    | 3   | 7.230mb   | 0.006761s  | ±1.65%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,10                | 1    | 3   | 7.208mb   | 0.000038s  | ±3.31%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,10                 | 1    | 3   | 7.208mb   | 0.000036s  | ±2.67%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,10             | 1    | 3   | 7.208mb   | 0.000038s  | ±3.31%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,10            | 1    | 3   | 7.290mb   | 0.000477s  | ±8.17%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,10           | 1    | 3   | 7.293mb   | 0.000578s  | ±6.35%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,10                | 1    | 3   | 7.215mb   | 0.000095s  | ±0.99%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,10                 | 1    | 3   | 7.215mb   | 0.000101s  | ±1.24%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,10                  | 1    | 3   | 7.263mb   | 0.000234s  | ±111.07% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,11                     | 1    | 3   | 7.676mb   | 0.004294s  | ±0.68%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,11                       | 1    | 3   | 7.654mb   | 0.000039s  | ±5.22%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,11                  | 1    | 3   | 7.653mb   | 0.000032s  | ±4.56%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,11  | 1    | 3   | 7.655mb   | 0.000036s  | ±6.26%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,11                  | 1    | 3   | 7.676mb   | 0.004028s  | ±3.51%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,11                 | 1    | 3   | 7.683mb   | 0.029958s  | ±4.71%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,11                   | 1    | 3   | 7.639mb   | 0.000005s  | ±8.84%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,11     | 1    | 3   | 7.676mb   | 0.002762s  | ±6.54%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,11         | 1    | 3   | 7.676mb   | 0.007102s  | ±0.87%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,11                | 1    | 3   | 7.655mb   | 0.000040s  | ±2.40%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,11                 | 1    | 3   | 7.655mb   | 0.000038s  | ±1.23%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,11             | 1    | 3   | 7.655mb   | 0.000039s  | ±2.38%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,11            | 1    | 3   | 7.751mb   | 0.000748s  | ±3.51%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,11           | 1    | 3   | 7.745mb   | 0.000702s  | ±20.15%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,11                | 1    | 3   | 7.708mb   | 0.000205s  | ±112.79% |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,11                 | 1    | 3   | 7.663mb   | 0.000130s  | ±4.27%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,11                  | 1    | 3   | 7.709mb   | 0.000356s  | ±116.94% |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,20                     | 1    | 3   | 12.464mb  | 0.008312s  | ±7.15%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,20                       | 1    | 3   | 12.464mb  | 0.000229s  | ±131.77% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,20                  | 1    | 3   | 12.411mb  | 0.000210s  | ±133.66% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,20  | 1    | 3   | 12.406mb  | 0.000061s  | ±2.03%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,20                  | 1    | 3   | 12.413mb  | 0.006806s  | ±0.73%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,20                 | 1    | 3   | 12.456mb  | 0.091459s  | ±1.23%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,20                   | 1    | 3   | 12.388mb  | 0.000005s  | ±23.57%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,20     | 1    | 3   | 12.453mb  | 0.004731s  | ±16.00%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,20         | 1    | 3   | 12.413mb  | 0.012274s  | ±2.14%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,20                | 1    | 3   | 12.406mb  | 0.000068s  | ±4.29%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,20                 | 1    | 3   | 12.406mb  | 0.000066s  | ±2.11%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,20             | 1    | 3   | 12.406mb  | 0.000065s  | ±1.91%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,20            | 1    | 3   | 12.702mb  | 0.013209s  | ±12.23%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,20           | 1    | 3   | 12.692mb  | 0.011353s  | ±25.71%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,20                | 1    | 3   | 12.464mb  | 0.000573s  | ±68.31%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,20                 | 1    | 3   | 12.445mb  | 0.000496s  | ±0.59%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,20                  | 1    | 3   | 12.444mb  | 0.000774s  | ±0.82%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,30                     | 1    | 3   | 16.946mb  | 0.011932s  | ±1.26%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,30                       | 1    | 3   | 16.966mb  | 0.000191s  | ±116.41% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,30                  | 1    | 3   | 16.956mb  | 0.000074s  | ±9.30%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,30  | 1    | 3   | 16.950mb  | 0.000107s  | ±2.78%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,30                  | 1    | 3   | 16.946mb  | 0.010802s  | ±1.99%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,30                 | 1    | 3   | 17.002mb  | 0.190024s  | ±1.38%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,30                   | 1    | 3   | 16.928mb  | 0.000008s  | ±14.97%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,30     | 1    | 3   | 16.990mb  | 0.008383s  | ±20.97%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,30         | 1    | 3   | 16.981mb  | 0.018721s  | ±15.29%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,30                | 1    | 3   | 17.014mb  | 0.000273s  | ±125.55% |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,30                 | 1    | 3   | 16.948mb  | 0.000103s  | ±13.59%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,30             | 1    | 3   | 16.948mb  | 0.000105s  | ±3.52%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,30            | 1    | 3   | 17.577mb  | 0.090871s  | ±11.64%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,30           | 1    | 3   | 17.571mb  | 0.100091s  | ±6.46%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,30                | 1    | 3   | 17.007mb  | 0.001403s  | ±1.58%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,30                 | 1    | 3   | 17.008mb  | 0.001483s  | ±1.41%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,30                  | 1    | 3   | 17.006mb  | 0.002474s  | ±22.77%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,40                     | 1    | 3   | 23.032mb  | 0.017442s  | ±16.43%  |
| MethodsNonProportionalBench | benchByCandidates | Copeland,40                       | 1    | 3   | 23.043mb  | 0.000399s  | ±127.17% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,40                  | 1    | 3   | 23.070mb  | 0.000351s  | ±132.04% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,40  | 1    | 3   | 23.003mb  | 0.000159s  | ±3.21%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,40                  | 1    | 3   | 22.990mb  | 0.014845s  | ±0.38%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,40                 | 1    | 3   | 23.066mb  | 0.328225s  | ±0.95%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,40                   | 1    | 3   | 22.981mb  | 0.000006s  | ±7.44%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,40     | 1    | 3   | 22.981mb  | 0.009713s  | ±0.70%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,40         | 1    | 3   | 22.999mb  | 0.026552s  | ±3.05%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,40                | 1    | 3   | 22.991mb  | 0.000150s  | ±1.66%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,40                 | 1    | 3   | 22.991mb  | 0.000150s  | ±1.92%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,40             | 1    | 3   | 22.993mb  | 0.000153s  | ±3.03%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,40            | 1    | 3   | 24.058mb  | 0.416988s  | ±8.69%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,40           | 1    | 3   | 24.113mb  | 0.412503s  | ±13.98%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,40                | 1    | 3   | 23.174mb  | 0.003404s  | ±2.64%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,40                 | 1    | 3   | 23.173mb  | 0.003211s  | ±0.75%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,40                  | 1    | 3   | 23.180mb  | 0.005581s  | ±2.42%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,50                     | 1    | 3   | 27.569mb  | 0.022380s  | ±0.49%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,50                       | 1    | 3   | 27.569mb  | 0.000227s  | ±4.03%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,50                  | 1    | 3   | 27.646mb  | 0.000231s  | ±112.45% |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,50  | 1    | 3   | 27.571mb  | 0.000223s  | ±3.56%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,50                  | 1    | 3   | 27.569mb  | 0.019937s  | ±0.16%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,50                 | 1    | 3   | 27.692mb  | 0.517816s  | ±9.10%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,50                   | 1    | 3   | 27.569mb  | 0.000007s  | ±11.66%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,50     | 1    | 3   | 27.569mb  | 0.012613s  | ±1.68%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,50         | 1    | 3   | 27.569mb  | 0.036053s  | ±1.19%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,50                | 1    | 3   | 27.569mb  | 0.000230s  | ±2.40%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,50                 | 1    | 3   | 27.569mb  | 0.000212s  | ±1.23%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,50             | 1    | 3   | 27.569mb  | 0.000220s  | ±9.60%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,50            | 1    | 3   | 29.229mb  | 1.345662s  | ±1.92%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,50           | 1    | 3   | 29.236mb  | 1.587747s  | ±5.93%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,50                | 1    | 3   | 27.786mb  | 0.005877s  | ±1.04%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,50                 | 1    | 3   | 27.788mb  | 0.006018s  | ±3.38%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,50                  | 1    | 3   | 27.788mb  | 0.010559s  | ±4.81%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,60                     | 1    | 3   | 32.125mb  | 0.028504s  | ±0.93%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,60                       | 1    | 3   | 32.133mb  | 0.000283s  | ±2.35%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,60                  | 1    | 3   | 32.200mb  | 0.000177s  | ±6.66%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,60  | 1    | 3   | 32.138mb  | 0.000298s  | ±1.10%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,60                  | 1    | 3   | 32.125mb  | 0.027369s  | ±1.79%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,60                 | 1    | 3   | 32.258mb  | 0.754619s  | ±6.02%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,60                   | 1    | 3   | 32.125mb  | 0.000007s  | ±11.66%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,60     | 1    | 3   | 32.125mb  | 0.016028s  | ±10.55%  |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,60         | 1    | 3   | 32.125mb  | 0.044753s  | ±5.16%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,60                | 1    | 3   | 32.125mb  | 0.000287s  | ±1.63%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,60                 | 1    | 3   | 32.126mb  | 0.000272s  | ±2.14%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,60             | 1    | 3   | 32.125mb  | 0.000278s  | ±2.67%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,60            | 1    | 3   | 34.508mb  | 3.316166s  | ±11.62%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,60           | 1    | 3   | 34.505mb  | 3.178537s  | ±8.28%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,60                | 1    | 3   | 32.398mb  | 0.010044s  | ±1.64%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,60                 | 1    | 3   | 32.399mb  | 0.010514s  | ±2.29%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,60                  | 1    | 3   | 32.401mb  | 0.018374s  | ±1.32%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,70                     | 1    | 3   | 44.389mb  | 0.035290s  | ±2.09%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,70                       | 1    | 3   | 44.389mb  | 0.000369s  | ±1.94%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,70                  | 1    | 3   | 44.618mb  | 0.000225s  | ±5.49%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,70  | 1    | 3   | 44.389mb  | 0.000465s  | ±81.93%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,70                  | 1    | 3   | 44.389mb  | 0.032422s  | ±1.44%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,70                 | 1    | 3   | 44.679mb  | 1.037296s  | ±10.52%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,70                   | 1    | 3   | 44.389mb  | 0.000007s  | ±11.66%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,70     | 1    | 3   | 44.389mb  | 0.019715s  | ±1.45%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,70         | 1    | 3   | 44.389mb  | 0.054181s  | ±0.77%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,70                | 1    | 3   | 44.389mb  | 0.000367s  | ±0.78%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,70                 | 1    | 3   | 44.389mb  | 0.000355s  | ±3.92%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,70             | 1    | 3   | 44.389mb  | 0.000376s  | ±2.39%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,70            | 1    | 3   | 47.592mb  | 9.151059s  | ±17.67%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,70           | 1    | 3   | 47.574mb  | 7.939814s  | ±10.66%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,70                | 1    | 3   | 45.430mb  | 0.016193s  | ±1.04%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,70                 | 1    | 3   | 45.429mb  | 0.016592s  | ±1.85%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,70                  | 1    | 3   | 45.433mb  | 0.029352s  | ±1.38%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,80                     | 1    | 3   | 49.226mb  | 0.044011s  | ±0.15%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,80                       | 1    | 3   | 49.226mb  | 0.000477s  | ±0.94%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,80                  | 1    | 3   | 49.522mb  | 0.000287s  | ±3.17%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,80  | 1    | 3   | 49.226mb  | 0.000470s  | ±3.63%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,80                  | 1    | 3   | 49.226mb  | 0.040215s  | ±0.08%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,80                 | 1    | 3   | 49.544mb  | 1.422216s  | ±7.65%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,80                   | 1    | 3   | 49.226mb  | 0.000007s  | ±0.00%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,80     | 1    | 3   | 49.226mb  | 0.024139s  | ±0.52%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,80         | 1    | 3   | 49.226mb  | 0.069787s  | ±7.07%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,80                | 1    | 3   | 49.226mb  | 0.000468s  | ±0.46%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,80                 | 1    | 3   | 49.226mb  | 0.000468s  | ±0.20%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,80             | 1    | 3   | 49.226mb  | 0.000473s  | ±2.93%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,80            | 1    | 3   | 53.379mb  | 17.652835s | ±19.68%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,80           | 1    | 3   | 53.414mb  | 16.093006s | ±12.74%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,80                | 1    | 3   | 50.432mb  | 0.024409s  | ±3.25%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,80                 | 1    | 3   | 50.433mb  | 0.024826s  | ±1.25%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,80                  | 1    | 3   | 50.437mb  | 0.044515s  | ±1.02%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,90                     | 1    | 3   | 54.063mb  | 0.051046s  | ±5.67%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,90                       | 1    | 3   | 54.063mb  | 0.000782s  | ±55.30%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,90                  | 1    | 3   | 54.355mb  | 0.000324s  | ±0.77%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,90  | 1    | 3   | 54.063mb  | 0.000590s  | ±1.98%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,90                  | 1    | 3   | 54.063mb  | 0.048183s  | ±2.90%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,90                 | 1    | 3   | 54.455mb  | 1.773768s  | ±7.30%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,90                   | 1    | 3   | 54.063mb  | 0.000007s  | ±7.07%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,90     | 1    | 3   | 54.063mb  | 0.027222s  | ±0.65%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,90         | 1    | 3   | 54.063mb  | 0.076666s  | ±1.09%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,90                | 1    | 3   | 54.063mb  | 0.000551s  | ±3.36%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,90                 | 1    | 3   | 54.063mb  | 0.000543s  | ±1.06%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,90             | 1    | 3   | 54.063mb  | 0.000540s  | ±4.98%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,90            | 1    | 3   | 59.248mb  | 30.973354s | ±8.26%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,90           | 1    | 3   | 59.257mb  | 30.272496s | ±12.70%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,90                | 1    | 3   | 55.438mb  | 0.034383s  | ±1.00%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,90                 | 1    | 3   | 55.440mb  | 0.033415s  | ±1.86%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,90                  | 1    | 3   | 55.440mb  | 0.060644s  | ±2.29%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,100                    | 1    | 3   | 59.031mb  | 0.060027s  | ±1.77%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,100                      | 1    | 3   | 59.031mb  | 0.000669s  | ±2.31%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,100                 | 1    | 3   | 59.329mb  | 0.000383s  | ±1.13%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,100 | 1    | 3   | 59.031mb  | 0.000664s  | ±1.62%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,100                 | 1    | 3   | 59.031mb  | 0.052348s  | ±0.86%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,100                | 1    | 3   | 59.224mb  | 1.946463s  | ±4.10%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,100                  | 1    | 3   | 59.031mb  | 0.000007s  | ±7.07%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,100    | 1    | 3   | 59.031mb  | 0.032341s  | ±1.92%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,100        | 1    | 3   | 59.031mb  | 0.089906s  | ±0.23%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,100               | 1    | 3   | 59.031mb  | 0.000647s  | ±2.31%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,100                | 1    | 3   | 59.031mb  | 0.000649s  | ±0.50%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,100            | 1    | 3   | 59.031mb  | 0.000638s  | ±0.37%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,100           | 1    | 3   | 65.349mb  | 58.002654s | ±7.60%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,100          | 1    | 3   | 65.212mb  | 50.089861s | ±4.77%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,100               | 1    | 3   | 60.436mb  | 0.044041s  | ±1.80%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,100                | 1    | 3   | 60.443mb  | 0.044564s  | ±1.62%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,100                 | 1    | 3   | 60.442mb  | 0.081513s  | ±0.27%   |
+-----------------------------+-------------------+-----------------------------------+------+-----+-----------+------------+----------+

### Proportional

Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+--------------------------+-------------------+---------+------+-----+----------+-----------+---------+
| benchmark                | subject           | set     | revs | its | mem_peak | mode      | rstdev  |
+--------------------------+-------------------+---------+------+-----+----------+-----------+---------+
| MethodsProportionalBench | benchByCandidates | STV,3   | 1    | 3   | 3.849mb  | 0.004075s | ±19.71% |
| MethodsProportionalBench | benchByCandidates | STV,4   | 1    | 3   | 4.228mb  | 0.006325s | ±14.87% |
| MethodsProportionalBench | benchByCandidates | STV,5   | 1    | 3   | 4.672mb  | 0.008955s | ±1.32%  |
| MethodsProportionalBench | benchByCandidates | STV,6   | 1    | 3   | 5.119mb  | 0.009425s | ±18.91% |
| MethodsProportionalBench | benchByCandidates | STV,7   | 1    | 3   | 5.563mb  | 0.014790s | ±14.17% |
| MethodsProportionalBench | benchByCandidates | STV,8   | 1    | 3   | 6.327mb  | 0.016129s | ±7.76%  |
| MethodsProportionalBench | benchByCandidates | STV,9   | 1    | 3   | 6.788mb  | 0.019538s | ±0.74%  |
| MethodsProportionalBench | benchByCandidates | STV,10  | 1    | 3   | 7.235mb  | 0.023839s | ±2.54%  |
| MethodsProportionalBench | benchByCandidates | STV,11  | 1    | 3   | 7.682mb  | 0.028752s | ±2.09%  |
| MethodsProportionalBench | benchByCandidates | STV,12  | 1    | 3   | 8.129mb  | 0.029920s | ±1.95%  |
| MethodsProportionalBench | benchByCandidates | STV,13  | 1    | 3   | 8.575mb  | 0.035721s | ±1.74%  |
| MethodsProportionalBench | benchByCandidates | STV,14  | 1    | 3   | 9.022mb  | 0.041041s | ±0.85%  |
| MethodsProportionalBench | benchByCandidates | STV,15  | 1    | 3   | 9.467mb  | 0.042278s | ±0.71%  |
| MethodsProportionalBench | benchByCandidates | STV,16  | 1    | 3   | 10.570mb | 0.048256s | ±1.55%  |
| MethodsProportionalBench | benchByCandidates | STV,17  | 1    | 3   | 11.073mb | 0.054690s | ±0.28%  |
| MethodsProportionalBench | benchByCandidates | STV,18  | 1    | 3   | 11.529mb | 0.057881s | ±12.45% |
| MethodsProportionalBench | benchByCandidates | STV,19  | 1    | 3   | 11.977mb | 0.065760s | ±1.32%  |
| MethodsProportionalBench | benchByCandidates | STV,20  | 1    | 3   | 12.428mb | 0.071830s | ±0.89%  |
| MethodsProportionalBench | benchByCandidates | STV,30  | 1    | 3   | 16.967mb | 0.146342s | ±3.07%  |
| MethodsProportionalBench | benchByCandidates | STV,40  | 1    | 3   | 23.056mb | 0.262451s | ±0.98%  |
| MethodsProportionalBench | benchByCandidates | STV,50  | 1    | 3   | 27.666mb | 0.438554s | ±1.09%  |
| MethodsProportionalBench | benchByCandidates | STV,60  | 1    | 3   | 32.266mb | 0.617602s | ±2.68%  |
| MethodsProportionalBench | benchByCandidates | STV,70  | 1    | 3   | 44.724mb | 0.877067s | ±2.16%  |
| MethodsProportionalBench | benchByCandidates | STV,80  | 1    | 3   | 49.657mb | 1.217623s | ±2.48%  |
| MethodsProportionalBench | benchByCandidates | STV,90  | 1    | 3   | 54.599mb | 1.660393s | ±2.53%  |
| MethodsProportionalBench | benchByCandidates | STV,100 | 1    | 3   | 59.679mb | 2.057089s | ±1.32%  |
+--------------------------+-------------------+---------+------+-----+----------+-----------+---------+