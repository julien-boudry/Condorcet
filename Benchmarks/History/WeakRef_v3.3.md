# Conclusion
When the garbage collector is actively used, there is no significant difference (memory used). If not, the implementation of weak references is much more economic.

# v3.2 Branch

## Time Centric
```
Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+------+---------------------+----------------------+-----+------+-------------+---------------+--------------+----------------+
| iter | benchmark           | subject              | set | revs | mem_peak    | time_avg      | comp_z_value | comp_deviation |
+------+---------------------+----------------------+-----+------+-------------+---------------+--------------+----------------+
| 0    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 27,103,632b | 258,943.200μs | -0.10σ       | -0.05%         |
| 1    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 27,103,632b | 257,405.200μs | -1.24σ       | -0.65%         |
| 2    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 27,103,632b | 261,179.300μs | +1.55σ       | +0.81%         |
| 3    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 27,103,632b | 258,782.800μs | -0.22σ       | -0.11%         |
+------+---------------------+----------------------+-----+------+-------------+---------------+--------------+----------------+

```

## Memory Centric
```
Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+------+---------------------+----------------------+-----+------+------------+-----------------+--------------+----------------+
| iter | benchmark           | subject              | set | revs | mem_peak   | time_avg        | comp_z_value | comp_deviation |
+------+---------------------+----------------------+-----+------+------------+-----------------+--------------+----------------+
| 0    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 6,883,080b | 2,605,364.000μs | -0.60σ       | -0.42%         |
| 1    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 6,883,080b | 2,591,650.000μs | -1.33σ       | -0.95%         |
| 2    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 6,883,080b | 2,633,507.000μs | +0.92σ       | +0.65%         |
| 3    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 6,883,080b | 2,635,192.000μs | +1.01σ       | +0.72%         |
+------+---------------------+----------------------+-----+------+------------+-----------------+--------------+----------------+

```


# v3.3 Branch

## Time Centric
```
Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+------+---------------------+----------------------+-----+------+------------+---------------+--------------+----------------+
| iter | benchmark           | subject              | set | revs | mem_peak   | time_avg      | comp_z_value | comp_deviation |
+------+---------------------+----------------------+-----+------+------------+---------------+--------------+----------------+
| 0    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 7,045,952b | 279,167.000μs | -0.30σ       | -0.12%         |
| 1    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 7,045,952b | 281,010.000μs | +1.30σ       | +0.54%         |
| 2    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 7,045,952b | 280,016.000μs | +0.43σ       | +0.18%         |
| 3    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 7,045,952b | 277,863.500μs | -1.43σ       | -0.59%         |
+------+---------------------+----------------------+-----+------+------------+---------------+--------------+----------------+
```

## Memory Centric
```
Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+------+---------------------+----------------------+-----+------+------------+-----------------+--------------+----------------+
| iter | benchmark           | subject              | set | revs | mem_peak   | time_avg        | comp_z_value | comp_deviation |
+------+---------------------+----------------------+-----+------+------------+-----------------+--------------+----------------+
| 0    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 7,041,560b | 2,847,455.000μs | +1.37σ       | +0.80%         |
| 1    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 7,041,560b | 2,801,896.000μs | -1.40σ       | -0.81%         |
| 2    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 7,041,560b | 2,829,893.000μs | +0.30σ       | +0.18%         |
| 3    | IntensiveUsageBench | benchSimpleManyVotes |     | 10   | 7,041,560b | 2,820,355.000μs | -0.28σ       | -0.16%         |
+------+---------------------+----------------------+-----+------+------------+-----------------+--------------+----------------+
```