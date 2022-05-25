# v3.4-alpha1 Branch

1000 random votes different for each test, variable number of candidates (look at column "set") 

* AMD Ryzen 9 5200X

PHPBench (1.2.5) 
with PHP version 8.1.6, xdebug ❌, opcache ✔ (with JIT Tracing)

### Non-Proportional
```php
    #[Bench\Warmup(0)]
    #[Bench\Iterations(10)]
    #[Bench\Revs(1)]
```

Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+-----------------------------+-------------------+-----------------------------------+------+-----+-----------+-------------+----------+
| benchmark                   | subject           | set                               | revs | its | mem_peak  | mode        | rstdev   |
+-----------------------------+-------------------+-----------------------------------+------+-----+-----------+-------------+----------+
| MethodsNonProportionalBench | benchByCandidates | BordaCount,3                      | 1    | 10  | 4.136mb   | 0.003484s   | ±4.55%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,3                        | 1    | 10  | 3.934mb   | 0.000135s   | ±53.99%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,3                   | 1    | 10  | 3.861mb   | 0.000099s   | ±13.79%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,3   | 1    | 10  | 3.863mb   | 0.000130s   | ±46.93%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,3                   | 1    | 10  | 3.893mb   | 0.003374s   | ±2.47%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,3                  | 1    | 10  | 3.894mb   | 0.008348s   | ±2.05%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,3                    | 1    | 10  | 3.932mb   | 0.000400s   | ±31.92%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,3      | 1    | 10  | 3.893mb   | 0.002885s   | ±2.57%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,3          | 1    | 10  | 3.894mb   | 0.006293s   | ±2.48%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,3                 | 1    | 10  | 3.863mb   | 0.000136s   | ±39.63%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,3                  | 1    | 10  | 3.863mb   | 0.000135s   | ±26.14%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,3              | 1    | 10  | 3.863mb   | 0.000137s   | ±27.16%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,3             | 1    | 10  | 3.868mb   | 0.000128s   | ±12.61%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,3            | 1    | 10  | 3.868mb   | 0.000126s   | ±8.81%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,3                 | 1    | 10  | 3.863mb   | 0.000110s   | ±13.22%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,3                  | 1    | 10  | 3.863mb   | 0.000111s   | ±11.77%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,3                   | 1    | 10  | 3.863mb   | 0.000114s   | ±2.31%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,5                      | 1    | 10  | 4.781mb   | 0.004750s   | ±1.38%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,5                        | 1    | 10  | 4.754mb   | 0.000151s   | ±39.76%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,5                   | 1    | 10  | 4.752mb   | 0.000113s   | ±7.50%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,5   | 1    | 10  | 4.754mb   | 0.000143s   | ±23.86%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,5                   | 1    | 10  | 4.782mb   | 0.004542s   | ±1.94%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,5                  | 1    | 10  | 4.822mb   | 0.016452s   | ±2.16%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,5                    | 1    | 10  | 4.880mb   | 0.000822s   | ±7.20%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,5      | 1    | 10  | 4.782mb   | 0.003466s   | ±1.68%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,5          | 1    | 10  | 4.782mb   | 0.007967s   | ±0.89%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,5                 | 1    | 10  | 4.754mb   | 0.000148s   | ±46.25%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,5                  | 1    | 10  | 4.754mb   | 0.000149s   | ±19.69%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,5              | 1    | 10  | 4.754mb   | 0.000154s   | ±21.94%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,5             | 1    | 10  | 4.774mb   | 0.000192s   | ±4.52%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,5            | 1    | 10  | 4.774mb   | 0.000194s   | ±11.31%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,5                 | 1    | 10  | 4.754mb   | 0.000134s   | ±11.38%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,5                  | 1    | 10  | 4.754mb   | 0.000136s   | ±5.34%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,5                   | 1    | 10  | 4.754mb   | 0.000140s   | ±12.89%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,6                      | 1    | 10  | 5.226mb   | 0.005376s   | ±0.89%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,6                        | 1    | 10  | 5.199mb   | 0.000148s   | ±40.51%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,6                   | 1    | 10  | 5.197mb   | 0.000113s   | ±15.74%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,6   | 1    | 10  | 5.200mb   | 0.000148s   | ±40.80%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,6                   | 1    | 10  | 5.226mb   | 0.005093s   | ±1.82%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,6                  | 1    | 10  | 5.228mb   | 0.021580s   | ±4.04%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,6                    | 1    | 10  | 5.981mb   | 0.003779s   | ±2.96%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,6      | 1    | 10  | 5.226mb   | 0.003835s   | ±1.86%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,6          | 1    | 10  | 5.226mb   | 0.008987s   | ±1.36%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,6                 | 1    | 10  | 5.199mb   | 0.000155s   | ±49.14%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,6                  | 1    | 10  | 5.199mb   | 0.000151s   | ±17.13%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,6              | 1    | 10  | 5.199mb   | 0.000150s   | ±18.53%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,6             | 1    | 10  | 5.228mb   | 0.000262s   | ±6.39%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,6            | 1    | 10  | 5.229mb   | 0.000271s   | ±8.87%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,6                 | 1    | 10  | 5.200mb   | 0.000155s   | ±17.11%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,6                  | 1    | 10  | 5.200mb   | 0.000157s   | ±8.07%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,6                   | 1    | 10  | 5.200mb   | 0.000159s   | ±8.36%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,7                      | 1    | 10  | 5.670mb   | 0.005970s   | ±1.17%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,7                        | 1    | 10  | 5.645mb   | 0.000159s   | ±22.32%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,7                   | 1    | 10  | 5.642mb   | 0.000114s   | ±16.83%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,7   | 1    | 10  | 5.645mb   | 0.000156s   | ±45.49%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,7                   | 1    | 10  | 5.670mb   | 0.005667s   | ±1.06%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,7                  | 1    | 10  | 5.673mb   | 0.026963s   | ±1.14%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,7                    | 1    | 10  | 11.332mb  | 0.030478s   | ±0.63%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,7      | 1    | 10  | 5.670mb   | 0.004155s   | ±2.46%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,7          | 1    | 10  | 5.670mb   | 0.009689s   | ±2.19%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,7                 | 1    | 10  | 5.645mb   | 0.000168s   | ±42.64%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,7                  | 1    | 10  | 5.645mb   | 0.000162s   | ±22.92%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,7              | 1    | 10  | 5.645mb   | 0.000159s   | ±13.88%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,7             | 1    | 10  | 5.689mb   | 0.000438s   | ±8.68%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,7            | 1    | 10  | 5.688mb   | 0.000417s   | ±8.43%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,7                 | 1    | 10  | 5.645mb   | 0.000185s   | ±9.91%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,7                  | 1    | 10  | 5.645mb   | 0.000177s   | ±3.92%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,7                   | 1    | 10  | 5.645mb   | 0.000192s   | ±21.91%  |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,8                      | 1    | 10  | 6.435mb   | 0.006614s   | ±2.13%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,8                        | 1    | 10  | 6.412mb   | 0.000162s   | ±35.91%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,8                   | 1    | 10  | 6.409mb   | 0.000122s   | ±13.87%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,8   | 1    | 10  | 6.412mb   | 0.000185s   | ±35.59%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,8                   | 1    | 10  | 6.435mb   | 0.006272s   | ±4.70%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,8                  | 1    | 10  | 6.438mb   | 0.032924s   | ±3.75%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,8                    | 1    | 10  | 83.176mb  | 0.305305s   | ±0.72%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,8      | 1    | 10  | 6.435mb   | 0.004400s   | ±2.47%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,8          | 1    | 10  | 6.435mb   | 0.010465s   | ±1.73%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,8                 | 1    | 10  | 6.412mb   | 0.000164s   | ±38.51%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,8                  | 1    | 10  | 6.412mb   | 0.000161s   | ±20.62%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,8              | 1    | 10  | 6.412mb   | 0.000165s   | ±18.90%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,8             | 1    | 10  | 6.466mb   | 0.000588s   | ±12.54%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,8            | 1    | 10  | 6.465mb   | 0.000621s   | ±9.69%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,8                 | 1    | 10  | 6.412mb   | 0.000232s   | ±7.31%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,8                  | 1    | 10  | 6.412mb   | 0.000227s   | ±12.31%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,8                   | 1    | 10  | 6.412mb   | 0.000218s   | ±9.51%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,9                      | 1    | 10  | 6.893mb   | 0.007221s   | ±0.93%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,9                        | 1    | 10  | 6.872mb   | 0.000173s   | ±13.11%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,9                   | 1    | 10  | 6.870mb   | 0.000127s   | ±4.88%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,9   | 1    | 10  | 6.872mb   | 0.000176s   | ±22.71%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,9                   | 1    | 10  | 6.893mb   | 0.006884s   | ±1.04%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,9                  | 1    | 10  | 6.899mb   | 0.039714s   | ±3.00%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,9                    | 1    | 10  | 672.151mb | 3.279185s   | ±1.01%   |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,9      | 1    | 10  | 6.893mb   | 0.004720s   | ±1.49%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,9          | 1    | 10  | 6.893mb   | 0.011291s   | ±5.08%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,9                 | 1    | 10  | 6.872mb   | 0.000177s   | ±39.09%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,9                  | 1    | 10  | 6.872mb   | 0.000181s   | ±33.20%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,9              | 1    | 10  | 6.872mb   | 0.000184s   | ±36.69%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,9             | 1    | 10  | 6.939mb   | 0.000857s   | ±15.11%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,9            | 1    | 10  | 6.942mb   | 0.000999s   | ±18.31%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,9                 | 1    | 10  | 6.875mb   | 0.000247s   | ±6.19%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,9                  | 1    | 10  | 6.875mb   | 0.000254s   | ±6.15%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,9                   | 1    | 10  | 6.875mb   | 0.000264s   | ±9.16%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,10                     | 1    | 10  | 7.341mb   | 0.007895s   | ±1.43%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,10                       | 1    | 10  | 7.320mb   | 0.000194s   | ±34.57%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,10                  | 1    | 10  | 7.319mb   | 0.000134s   | ±26.40%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,10  | 1    | 10  | 7.321mb   | 0.000175s   | ±44.37%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,10                  | 1    | 10  | 7.341mb   | 0.007530s   | ±0.76%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,10                 | 1    | 10  | 7.348mb   | 0.047581s   | ±1.77%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,10                   | 1    | 10  | 7.368mb   | 0.000087s   | ±104.79% |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,10     | 1    | 10  | 7.341mb   | 0.004967s   | ±2.14%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,10         | 1    | 10  | 7.341mb   | 0.012203s   | ±1.35%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,10                | 1    | 10  | 7.320mb   | 0.000181s   | ±38.62%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,10                 | 1    | 10  | 7.321mb   | 0.000181s   | ±32.28%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,10             | 1    | 10  | 7.321mb   | 0.000188s   | ±35.41%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,10            | 1    | 10  | 7.405mb   | 0.001659s   | ±13.51%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,10           | 1    | 10  | 7.401mb   | 0.001547s   | ±13.44%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,10                | 1    | 10  | 7.328mb   | 0.000291s   | ±5.03%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,10                 | 1    | 10  | 7.328mb   | 0.000305s   | ±6.70%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,10                  | 1    | 10  | 7.328mb   | 0.000305s   | ±3.95%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,20                     | 1    | 10  | 12.508mb  | 0.014742s   | ±1.02%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,20                       | 1    | 10  | 12.502mb  | 0.000284s   | ±24.61%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,20                  | 1    | 10  | 12.506mb  | 0.000201s   | ±4.24%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,20  | 1    | 10  | 12.503mb  | 0.000275s   | ±14.24%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,20                  | 1    | 10  | 12.508mb  | 0.013954s   | ±1.40%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,20                 | 1    | 10  | 12.528mb  | 0.142892s   | ±4.11%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,20                   | 1    | 10  | 12.473mb  | 0.000117s   | ±57.54%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,20     | 1    | 10  | 12.507mb  | 0.008262s   | ±1.11%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,20         | 1    | 10  | 12.507mb  | 0.021268s   | ±2.02%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,20                | 1    | 10  | 12.502mb  | 0.000283s   | ±34.77%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,20                 | 1    | 10  | 12.502mb  | 0.000277s   | ±24.08%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,20             | 1    | 10  | 12.502mb  | 0.000287s   | ±26.50%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,20            | 1    | 10  | 12.795mb  | 0.037596s   | ±12.98%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,20           | 1    | 10  | 12.797mb  | 0.041004s   | ±14.75%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,20                | 1    | 10  | 12.541mb  | 0.001445s   | ±5.00%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,20                 | 1    | 10  | 12.541mb  | 0.001474s   | ±3.83%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,20                  | 1    | 10  | 12.542mb  | 0.001516s   | ±3.23%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,30                     | 1    | 10  | 17.040mb  | 0.021942s   | ±2.05%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,30                       | 1    | 10  | 17.046mb  | 0.000408s   | ±19.10%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,30                  | 1    | 10  | 17.055mb  | 0.000270s   | ±3.87%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,30  | 1    | 10  | 17.047mb  | 0.000415s   | ±18.17%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,30                  | 1    | 10  | 17.040mb  | 0.020905s   | ±3.15%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,30                 | 1    | 10  | 17.079mb  | 0.313259s   | ±8.35%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,30                   | 1    | 10  | 17.006mb  | 0.000125s   | ±59.82%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,30     | 1    | 10  | 17.039mb  | 0.011688s   | ±1.09%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,30         | 1    | 10  | 17.039mb  | 0.031141s   | ±0.97%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,30                | 1    | 10  | 17.044mb  | 0.000401s   | ±22.15%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,30                 | 1    | 10  | 17.045mb  | 0.000402s   | ±19.43%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,30             | 1    | 10  | 17.044mb  | 0.000394s   | ±18.57%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,30            | 1    | 10  | 17.699mb  | 0.340993s   | ±8.85%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,30           | 1    | 10  | 17.689mb  | 0.284956s   | ±10.93%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,30                | 1    | 10  | 17.103mb  | 0.004292s   | ±3.85%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,30                 | 1    | 10  | 17.104mb  | 0.004325s   | ±2.31%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,30                  | 1    | 10  | 17.104mb  | 0.004500s   | ±1.50%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,40                     | 1    | 10  | 23.086mb  | 0.029978s   | ±0.89%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,40                       | 1    | 10  | 23.097mb  | 0.000558s   | ±13.90%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,40                  | 1    | 10  | 23.139mb  | 0.000330s   | ±22.43%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,40  | 1    | 10  | 23.099mb  | 0.000558s   | ±16.54%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,40                  | 1    | 10  | 23.086mb  | 0.028327s   | ±0.85%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,40                 | 1    | 10  | 23.173mb  | 0.526411s   | ±5.37%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,40                   | 1    | 10  | 23.044mb  | 0.000139s   | ±53.69%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,40     | 1    | 10  | 23.072mb  | 0.015634s   | ±1.15%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,40         | 1    | 10  | 23.072mb  | 0.042163s   | ±1.84%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,40                | 1    | 10  | 23.089mb  | 0.000551s   | ±15.50%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,40                 | 1    | 10  | 23.089mb  | 0.000546s   | ±13.52%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,40             | 1    | 10  | 23.089mb  | 0.000556s   | ±16.00%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,40            | 1    | 10  | 24.226mb  | 1.446236s   | ±13.18%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,40           | 1    | 10  | 24.196mb  | 1.285466s   | ±7.62%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,40                | 1    | 10  | 23.275mb  | 0.009895s   | ±1.68%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,40                 | 1    | 10  | 23.276mb  | 0.010097s   | ±2.33%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,40                  | 1    | 10  | 23.277mb  | 0.010258s   | ±2.20%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,50                     | 1    | 10  | 27.650mb  | 0.038032s   | ±1.57%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,50                       | 1    | 10  | 27.664mb  | 0.000730s   | ±10.61%  |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,50                  | 1    | 10  | 27.721mb  | 0.000415s   | ±6.98%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,50  | 1    | 10  | 27.667mb  | 0.000744s   | ±12.48%  |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,50                  | 1    | 10  | 27.650mb  | 0.036213s   | ±1.17%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,50                 | 1    | 10  | 27.786mb  | 0.853840s   | ±5.41%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,50                   | 1    | 10  | 27.600mb  | 0.000134s   | ±29.87%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,50     | 1    | 10  | 27.627mb  | 0.019691s   | ±1.31%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,50         | 1    | 10  | 27.627mb  | 0.053810s   | ±1.60%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,50                | 1    | 10  | 27.652mb  | 0.000721s   | ±13.98%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,50                 | 1    | 10  | 27.653mb  | 0.000713s   | ±14.75%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,50             | 1    | 10  | 27.653mb  | 0.000713s   | ±16.27%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,50            | 1    | 10  | 29.326mb  | 4.567310s   | ±6.81%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,50           | 1    | 10  | 29.341mb  | 4.272585s   | ±11.50%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,50                | 1    | 10  | 27.885mb  | 0.018709s   | ±1.89%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,50                 | 1    | 10  | 27.887mb  | 0.019044s   | ±1.40%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,50                  | 1    | 10  | 27.885mb  | 0.019501s   | ±1.90%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,60                     | 1    | 10  | 32.214mb  | 0.046710s   | ±1.31%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,60                       | 1    | 10  | 32.230mb  | 0.000944s   | ±9.60%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,60                  | 1    | 10  | 32.303mb  | 0.000511s   | ±6.11%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,60  | 1    | 10  | 32.234mb  | 0.000979s   | ±9.51%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,60                  | 1    | 10  | 32.214mb  | 0.044510s   | ±1.58%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,60                 | 1    | 10  | 32.372mb  | 1.111983s   | ±7.93%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,60                   | 1    | 10  | 32.156mb  | 0.000137s   | ±57.35%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,60     | 1    | 10  | 32.183mb  | 0.023826s   | ±0.78%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,60         | 1    | 10  | 32.183mb  | 0.065455s   | ±1.47%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,60                | 1    | 10  | 32.221mb  | 0.000937s   | ±9.02%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,60                 | 1    | 10  | 32.223mb  | 0.000958s   | ±7.75%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,60             | 1    | 10  | 32.221mb  | 0.000947s   | ±8.39%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,60            | 1    | 10  | 34.756mb  | 10.341434s  | ±13.75%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,60           | 1    | 10  | 34.619mb  | 9.996764s   | ±13.68%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,60                | 1    | 10  | 32.498mb  | 0.031525s   | ±5.22%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,60                 | 1    | 10  | 32.495mb  | 0.032043s   | ±1.46%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,60                  | 1    | 10  | 32.496mb  | 0.032891s   | ±1.91%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,70                     | 1    | 10  | 44.438mb  | 0.055931s   | ±1.00%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,70                       | 1    | 10  | 44.434mb  | 0.001201s   | ±5.55%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,70                  | 1    | 10  | 44.720mb  | 0.000648s   | ±4.79%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,70  | 1    | 10  | 44.461mb  | 0.001249s   | ±8.48%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,70                  | 1    | 10  | 44.438mb  | 0.053406s   | ±1.06%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,70                 | 1    | 10  | 44.778mb  | 1.535533s   | ±7.61%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,70                   | 1    | 10  | 44.360mb  | 0.000139s   | ±35.12%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,70     | 1    | 10  | 44.388mb  | 0.028497s   | ±0.74%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,70         | 1    | 10  | 44.388mb  | 0.078627s   | ±1.56%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,70                | 1    | 10  | 44.421mb  | 0.001384s   | ±8.79%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,70                 | 1    | 10  | 44.419mb  | 0.001406s   | ±9.96%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,70             | 1    | 10  | 44.421mb  | 0.001154s   | ±9.27%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,70            | 1    | 10  | 47.755mb  | 24.194390s  | ±11.68%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,70           | 1    | 10  | 47.696mb  | 23.240746s  | ±14.44%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,70                | 1    | 10  | 45.530mb  | 0.049762s   | ±1.82%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,70                 | 1    | 10  | 45.531mb  | 0.050873s   | ±2.61%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,70                  | 1    | 10  | 45.530mb  | 0.052159s   | ±2.18%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,80                     | 1    | 10  | 49.283mb  | 0.065680s   | ±1.29%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,80                       | 1    | 10  | 49.305mb  | 0.001520s   | ±6.55%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,80                  | 1    | 10  | 49.659mb  | 0.000779s   | ±5.46%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,80  | 1    | 10  | 49.310mb  | 0.001531s   | ±6.08%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,80                  | 1    | 10  | 49.283mb  | 0.062751s   | ±1.93%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,80                 | 1    | 10  | 49.705mb  | 1.899876s   | ±9.52%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,80                   | 1    | 10  | 49.197mb  | 0.000139s   | ±35.85%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,80     | 1    | 10  | 49.230mb  | 0.033407s   | ±0.54%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,80         | 1    | 10  | 49.230mb  | 0.094491s   | ±1.60%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,80                | 1    | 10  | 49.266mb  | 0.001432s   | ±10.22%  |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,80                 | 1    | 10  | 49.265mb  | 0.001443s   | ±9.43%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,80             | 1    | 10  | 49.267mb  | 0.001443s   | ±8.94%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,80            | 1    | 10  | 53.506mb  | 47.657385s  | ±12.48%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,80           | 1    | 10  | 53.494mb  | 47.212479s  | ±11.44%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,80                | 1    | 10  | 50.538mb  | 0.073282s   | ±1.78%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,80                 | 1    | 10  | 50.533mb  | 0.074385s   | ±1.62%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,80                  | 1    | 10  | 50.533mb  | 0.076457s   | ±1.76%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,90                     | 1    | 10  | 54.129mb  | 0.075801s   | ±1.14%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,90                       | 1    | 10  | 54.151mb  | 0.001846s   | ±4.92%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,90                  | 1    | 10  | 54.527mb  | 0.000913s   | ±7.53%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,90  | 1    | 10  | 54.158mb  | 0.001831s   | ±5.93%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,90                  | 1    | 10  | 54.129mb  | 0.072465s   | ±0.50%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,90                 | 1    | 10  | 54.563mb  | 2.562778s   | ±5.73%   |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,90                   | 1    | 10  | 54.035mb  | 0.000138s   | ±31.97%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,90     | 1    | 10  | 54.071mb  | 0.037701s   | ±1.73%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,90         | 1    | 10  | 54.071mb  | 0.109739s   | ±1.11%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,90                | 1    | 10  | 54.108mb  | 0.001772s   | ±6.40%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,90                 | 1    | 10  | 54.110mb  | 0.001796s   | ±5.19%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,90             | 1    | 10  | 54.106mb  | 0.001791s   | ±5.45%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,90            | 1    | 10  | 59.668mb  | 81.695565s  | ±13.40%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,90           | 1    | 10  | 59.345mb  | 76.330617s  | ±9.22%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,90                | 1    | 10  | 55.535mb  | 0.104271s   | ±2.11%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,90                 | 1    | 10  | 55.534mb  | 0.104755s   | ±1.19%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,90                  | 1    | 10  | 55.536mb  | 0.107350s   | ±1.93%   |
| MethodsNonProportionalBench | benchByCandidates | BordaCount,100                    | 1    | 10  | 58.975mb  | 0.086154s   | ±0.58%   |
| MethodsNonProportionalBench | benchByCandidates | Copeland,100                      | 1    | 10  | 59.001mb  | 0.002237s   | ±3.57%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Quick,100                 | 1    | 10  | 59.399mb  | 0.001078s   | ±7.08%   |
| MethodsNonProportionalBench | benchByCandidates | Dodgson Tideman Approximation,100 | 1    | 10  | 59.008mb  | 0.002175s   | ±3.28%   |
| MethodsNonProportionalBench | benchByCandidates | DowdallSystem,100                 | 1    | 10  | 58.975mb  | 0.080650s   | ±1.20%   |
| MethodsNonProportionalBench | benchByCandidates | Instant-runoff,100                | 1    | 10  | 59.426mb  | 3.064430s   | ±10.08%  |
| MethodsNonProportionalBench | benchByCandidates | Kemeny–Young,100                  | 1    | 10  | 58.872mb  | 0.000138s   | ±32.67%  |
| MethodsNonProportionalBench | benchByCandidates | First-past-the-post voting,100    | 1    | 10  | 58.912mb  | 0.043282s   | ±0.64%   |
| MethodsNonProportionalBench | benchByCandidates | Multiple Rounds System,100        | 1    | 10  | 58.912mb  | 0.124669s   | ±1.49%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Winning,100               | 1    | 10  | 58.952mb  | 0.002125s   | ±3.13%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Margin,100                | 1    | 10  | 58.954mb  | 0.002060s   | ±4.11%   |
| MethodsNonProportionalBench | benchByCandidates | Minimax Opposition,100            | 1    | 10  | 58.952mb  | 0.002170s   | ±3.57%   |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Margin,100           | 1    | 10  | 65.529mb  | 112.527491s | ±15.53%  |
| MethodsNonProportionalBench | benchByCandidates | Ranked Pairs Winning,100          | 1    | 10  | 65.426mb  | 122.701462s | ±11.12%  |
| MethodsNonProportionalBench | benchByCandidates | Schulze Winning,100               | 1    | 10  | 60.538mb  | 0.140950s   | ±6.99%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Margin,100                | 1    | 10  | 60.565mb  | 0.141597s   | ±2.57%   |
| MethodsNonProportionalBench | benchByCandidates | Schulze Ratio,100                 | 1    | 10  | 60.537mb  | 0.145471s   | ±1.54%   |
+-----------------------------+-------------------+-----------------------------------+------+-----+-----------+-------------+----------+

### Non-Proportional
```php
    #[Bench\Warmup(0)]
    #[Bench\Iterations(4)]
    #[Bench\Revs(1)]
```

Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+--------------------------+-------------------+-------------+------+-----+-----------+-------------+---------+
| benchmark                | subject           | set         | revs | its | mem_peak  | mode        | rstdev  |
+--------------------------+-------------------+-------------+------+-----+-----------+-------------+---------+
| MethodsProportionalBench | benchByCandidates | STV,3       | 1    | 4   | 6.002mb   | 0.005563s   | ±4.87%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,3   | 1    | 4   | 5.950mb   | 0.012801s   | ±2.48%  |
| MethodsProportionalBench | benchByCandidates | STV,4       | 1    | 4   | 6.579mb   | 0.007533s   | ±1.67%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,4   | 1    | 4   | 6.753mb   | 0.021944s   | ±1.97%  |
| MethodsProportionalBench | benchByCandidates | STV,5       | 1    | 4   | 7.399mb   | 0.009338s   | ±7.76%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,5   | 1    | 4   | 7.652mb   | 0.034776s   | ±0.17%  |
| MethodsProportionalBench | benchByCandidates | STV,6       | 1    | 4   | 8.222mb   | 0.009697s   | ±9.61%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,6   | 1    | 4   | 10.395mb  | 0.307382s   | ±2.26%  |
| MethodsProportionalBench | benchByCandidates | STV,7       | 1    | 4   | 9.042mb   | 0.011607s   | ±7.53%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,7   | 1    | 4   | 13.321mb  | 0.631552s   | ±0.76%  |
| MethodsProportionalBench | benchByCandidates | STV,8       | 1    | 4   | 10.503mb  | 0.013800s   | ±4.29%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,8   | 1    | 4   | 18.020mb  | 1.125488s   | ±1.32%  |
| MethodsProportionalBench | benchByCandidates | STV,9       | 1    | 4   | 11.339mb  | 0.015371s   | ±5.94%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,9   | 1    | 4   | 83.418mb  | 10.414018s  | ±1.12%  |
| MethodsProportionalBench | benchByCandidates | STV,10      | 1    | 4   | 12.162mb  | 0.016959s   | ±2.29%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,10  | 1    | 4   | 155.653mb | 21.520604s  | ±0.90%  |
| MethodsProportionalBench | benchByCandidates | STV,11      | 1    | 4   | 12.985mb  | 0.018814s   | ±1.47%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,11  | 1    | 4   | 286.687mb | 42.241524s  | ±0.83%  |
| MethodsProportionalBench | benchByCandidates | STV,12      | 1    | 4   | 13.808mb  | 0.019474s   | ±3.05%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,12  | 1    | 4   | 2.417gb   | 448.743978s | ±0.26%  |
| MethodsProportionalBench | benchByCandidates | STV,13      | 1    | 4   | 14.629mb  | 0.021053s   | ±14.98% |
| MethodsProportionalBench | benchByCandidates | CPO STV,13  | 1    | 4   | 8.707mb   | 0.000121s   | ±88.47% |
| MethodsProportionalBench | benchByCandidates | STV,14      | 1    | 4   | 15.453mb  | 0.023342s   | ±2.41%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,14  | 1    | 4   | 9.088mb   | 0.000145s   | ±45.94% |
| MethodsProportionalBench | benchByCandidates | STV,15      | 1    | 4   | 16.275mb  | 0.022572s   | ±0.78%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,15  | 1    | 4   | 9.534mb   | 0.000130s   | ±7.77%  |
| MethodsProportionalBench | benchByCandidates | STV,16      | 1    | 4   | 18.377mb  | 0.025342s   | ±1.25%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,16  | 1    | 4   | 10.621mb  | 0.000157s   | ±43.97% |
| MethodsProportionalBench | benchByCandidates | STV,17      | 1    | 4   | 19.258mb  | 0.027094s   | ±1.44%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,17  | 1    | 4   | 11.122mb  | 0.000126s   | ±18.36% |
| MethodsProportionalBench | benchByCandidates | STV,18      | 1    | 4   | 20.085mb  | 0.027141s   | ±1.41%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,18  | 1    | 4   | 11.575mb  | 0.000145s   | ±54.13% |
| MethodsProportionalBench | benchByCandidates | STV,19      | 1    | 4   | 20.911mb  | 0.029336s   | ±1.75%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,19  | 1    | 4   | 12.024mb  | 0.000145s   | ±14.91% |
| MethodsProportionalBench | benchByCandidates | STV,20      | 1    | 4   | 21.740mb  | 0.031326s   | ±1.71%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,20  | 1    | 4   | 12.473mb  | 0.000151s   | ±51.24% |
| MethodsProportionalBench | benchByCandidates | STV,30      | 1    | 4   | 30.042mb  | 0.043124s   | ±3.66%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,30  | 1    | 4   | 17.006mb  | 0.000157s   | ±53.67% |
| MethodsProportionalBench | benchByCandidates | STV,40      | 1    | 4   | 41.165mb  | 0.058566s   | ±2.96%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,40  | 1    | 4   | 23.044mb  | 0.000160s   | ±50.72% |
| MethodsProportionalBench | benchByCandidates | STV,50      | 1    | 4   | 49.534mb  | 0.073833s   | ±1.05%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,50  | 1    | 4   | 27.600mb  | 0.000195s   | ±40.87% |
| MethodsProportionalBench | benchByCandidates | STV,60      | 1    | 4   | 57.896mb  | 0.085580s   | ±1.89%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,60  | 1    | 4   | 32.156mb  | 0.000169s   | ±46.47% |
| MethodsProportionalBench | benchByCandidates | STV,70      | 1    | 4   | 79.741mb  | 0.108306s   | ±2.04%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,70  | 1    | 4   | 44.360mb  | 0.000199s   | ±41.83% |
| MethodsProportionalBench | benchByCandidates | STV,80      | 1    | 4   | 88.469mb  | 0.127090s   | ±1.89%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,80  | 1    | 4   | 49.197mb  | 0.000421s   | ±28.62% |
| MethodsProportionalBench | benchByCandidates | STV,90      | 1    | 4   | 97.201mb  | 0.145897s   | ±1.20%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,90  | 1    | 4   | 54.035mb  | 0.000203s   | ±33.59% |
| MethodsProportionalBench | benchByCandidates | STV,100     | 1    | 4   | 105.937mb | 0.160010s   | ±1.29%  |
| MethodsProportionalBench | benchByCandidates | CPO STV,100 | 1    | 4   | 58.872mb  | 0.000414s   | ±24.59% |
+--------------------------+-------------------+-------------+------+-----+-----------+-------------+---------+