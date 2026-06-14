# Question 6 - Investigating the Runtime Degradation

My first step would be to identify where the additional time is being spent.

Since the system is composed of a wrapper and two internal modules, I would measure the execution time of each component separately to determine whether the slowdown comes from Module A, Module B, or the wrapper itself.

After identifying the affected component, I would compare what changed between June and July, such as:

* New deployments or code changes
* Configuration changes
* Increased data volume
* Higher system usage
* Infrastructure changes

I would also review logs and monitor metrics such as CPU, memory, database performance, and external API response times to identify any bottlenecks.

In summary, my approach would be:

1. Measure the runtime of each module independently.
2. Identify which component is causing the degradation.
3. Compare changes made before the issue appeared.
4. Analyze logs and infrastructure metrics.
5. Determine the root cause and implement corrective actions.

This approach helps isolate the problem quickly and focus the investigation on the component responsible for the increased runtime.
