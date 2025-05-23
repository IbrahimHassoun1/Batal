import 'package:flutter/material.dart';
import 'package:flutter_slidable/flutter_slidable.dart';
import './my_workout_logic.dart';

class ExerciseRow extends StatefulWidget {
  final String title;
  final String imageUrl;
  final int initialCount;
  final int id;

  const ExerciseRow({
    super.key,
    required this.title,
    required this.imageUrl,
    required this.id,
    this.initialCount = 1,
  });

  @override
  State<ExerciseRow> createState() => ExerciseRowState();
}

class ExerciseRowState extends State<ExerciseRow> {
  int count = 1;
  late int id ;

  @override
  void initState() {
    super.initState();
    count = widget.initialCount;
    id = widget.id;
  }

  @override
  Widget build(BuildContext context) {
     if( count == 0) return SizedBox(height: 0,) ;
     return SizedBox(
      child: Slidable(
        
        key: Key(widget.title),
        endActionPane: ActionPane(
          motion: const StretchMotion(), 
          extentRatio: 0.6, 
          children: [
            SlidableAction(
              onPressed: (context) {
                completeExercice(this);
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('${widget.title} done!')),
                );
              },
              backgroundColor: Colors.green,
              icon: Icons.check,
              label: 'Done',
            ),
            SlidableAction(
              onPressed: (context) {
                removeExercice(this);
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('${widget.title} delete clicked')),
                );
              },
              backgroundColor: Colors.red,
              icon: Icons.delete,
              label: 'Delete',
            ),
          ],
        ),
        child: Padding(
          padding: const EdgeInsets.only(left: 16),
          child: ListTile(
            leading: widget.imageUrl.isNotEmpty
                ? SizedBox(height: 48,width: 48,child: CircleAvatar(backgroundImage: NetworkImage(widget.imageUrl)))
                : null,
            title: Text(widget.title),
            trailing: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                IconButton(
                  icon: Icon(Icons.remove_circle),
                  onPressed: () {
                    decrementSets(this);
                  },
                ),
                Text(count.toString(), style: TextStyle(fontSize: 16)),
                IconButton(
                  icon: Icon(Icons.add_circle),
                  onPressed: () {
                    incrementSets(this);
                  },
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
