## Instructions

### Simple Suite

#### Time Centric

``` ./vendor/bin/phpbench run Benchmarks/SimpleUsageBench.php --report=default ```

#### Memory Centric

``` ./vendor/bin/phpbench run Benchmarks/SimpleUsageBench.php --report=default --executor=memory_centric_microtime ```

#### Intensive Suite

``` ./vendor/bin/phpbench run Benchmarks/IntensiveUsageBench.php --report=default ```

### Run Specifics developement benchmarks

#### Pairwise Optimisation on Update

``` ./vendor/bin/phpbench run Benchmarks/PairwiseUpdateOptimizationBench.php --report=default --executor=memory_centric_microtime ```
