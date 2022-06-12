# v4.0-beta1 Branch

* AMD Ryzen 9 5900X

PHPBench (1.2.5) 
with PHP version 8.1.6, xdebug ❌, opcache ✔ (with JIT Tracing)

```php
    #[Bench\Warmup(1)]
    #[Bench\Iterations(3)]
    #[Bench\Revs(1)]
```

# v4.0-beta1 Branch

Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+------+---------------+------------------------------+-----+------+-------------+----------+--------------+----------------+
| iter | benchmark     | subject                      | set | revs | mem_peak    | time_avg | comp_z_value | comp_deviation |
+------+---------------+------------------------------+-----+------+-------------+----------+--------------+----------------+
| 0    | AddVotesBench | benchVotesWithManyCandidates |     | 1    | 59,269,648b | 0.714s   | -1.14σ       | -0.10%         |
| 1    | AddVotesBench | benchVotesWithManyCandidates |     | 1    | 58,871,856b | 0.715s   | -0.16σ       | -0.01%         |
| 2    | AddVotesBench | benchVotesWithManyCandidates |     | 1    | 58,871,856b | 0.716s   | +1.30σ       | +0.12%         |
+------+---------------+------------------------------+-----+------+-------------+----------+--------------+----------------+


# v3.3.3

Subjects: 1, Assertions: 0, Failures: 0, Errors: 0
+------+---------------+------------------------------+-----+------+-------------+----------+--------------+----------------+
| iter | benchmark     | subject                      | set | revs | mem_peak    | time_avg | comp_z_value | comp_deviation |
+------+---------------+------------------------------+-----+------+-------------+----------+--------------+----------------+
| 0    | AddVotesBench | benchVotesWithManyCandidates |     | 1    | 59,029,584b | 18.906s  | -1.00σ       | -0.69%         |
| 1    | AddVotesBench | benchVotesWithManyCandidates |     | 1    | 59,029,504b | 18.990s  | -0.36σ       | -0.25%         |
| 2    | AddVotesBench | benchVotesWithManyCandidates |     | 1    | 59,030,016b | 19.218s  | +1.36σ       | +0.94%         |
+------+---------------+------------------------------+-----+------+-------------+----------+--------------+----------------+

# v3.2

+------+---------------+------------------------------+-----+------+--------------+----------+--------------+----------------+
| iter | benchmark     | subject                      | set | revs | mem_peak     | time_avg | comp_z_value | comp_deviation |
+------+---------------+------------------------------+-----+------+--------------+----------+--------------+----------------+
| 0    | AddVotesBench | benchVotesWithManyCandidates |     | 1    | 117,355,360b | 18.869s  | +0.96σ       | +0.84%         |
| 1    | AddVotesBench | benchVotesWithManyCandidates |     | 1    | 117,023,280b | 18.488s  | -1.38σ       | -1.20%         |
| 2    | AddVotesBench | benchVotesWithManyCandidates |     | 1    | 117,023,280b | 18.780s  | +0.42σ       | +0.36%         |
+------+---------------+------------------------------+-----+------+--------------+----------+--------------+----------------+