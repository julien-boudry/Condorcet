# v3.4-alpha1 Branch

1000 random votes different for each test, variable number of candidates (look at column "set") 

* AMD Ryzen 9 5200X

PHPBench (1.2.5) 
with PHP version 8.1.6, xdebug ❌, opcache ✔ (with JIT Tracing)

### Non-Proportional
```php
    #[Bench\Warmup(1)]
    #[Bench\Iterations(10)]
    #[Bench\Revs(1)]
```

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
```php
    #[Bench\Warmup(0)]
    #[Bench\Iterations(4)]
    #[Bench\Revs(1)]
```

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