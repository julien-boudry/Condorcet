## Instructions

``` composer require --dev phpbench/phpbench ```

### Simple Suite
#### Time Centric
``` ./vendor/bin/phpbench run Benchmarks/SimpleUsageBench.php --report=default ```
#### Memory Centric
``` ./vendor/bin/phpbench run Benchmarks/SimpleUsageBench.php --report=default --executor=memory_centric_microtime ```


### Intensive Suite
#### Time Centric
``` ./vendor/bin/phpbench run Benchmarks/IntensiveUsageBench.php --report=default ```
#### Memory Centric
``` ./vendor/bin/phpbench run Benchmarks/IntensiveUsageBench.php --report=default  --executor=memory_centric_microtime ```


### Run Specifics developement benchmarks

#### Pairwise Optimisation on Update (between commits)

``` ./vendor/bin/phpbench run Benchmarks/PairwiseUpdateOptimizationBench.php --report=default --executor=memory_centric_microtime ```

#### Pairwse and addVote performance related to election number of candidates

``` ./vendor/bin/phpbench run Benchmarks/PairwiseNumberOfCandidatesBench.php --report=default ```

#### Methods speed test by Candidates numbers

``` ./vendor/bin/phpbench run Benchmarks/MethodsNonProportionalBench.php --report=aggregate ```
``` ./vendor/bin/phpbench run Benchmarks/MethodsProportionalBench.php --report=aggregate ```

#### Add Votes (1000 votes with 100 candidates)
``` ./vendor/bin/phpbench run Benchmarks/AddVotesBench.php --report=default ```

#### Kemeny-Young Speed & Memory Test

``` ./vendor/bin/phpbench run Benchmarks/KemenyYoungBench.php --report=default --executor=memory_centric_microtime ```