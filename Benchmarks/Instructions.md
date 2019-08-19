## Instructions

### Run Classic Suite

``` ./vendor/bin/phpbench run Benchmarks/BasicUsageBench.php --report=default ```

### Run Specifics developement benchmarks

#### Pairwise Optimisation on Update

``` ./vendor/bin/phpbench run Benchmarks/PairwiseUpdateOptimizationBench.php --report=default --executor=memory_centric_microtime ```
