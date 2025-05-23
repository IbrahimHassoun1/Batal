import 'package:flutter/material.dart';
import 'package:camera/camera.dart';
import 'package:provider/provider.dart';
import '../../../../core/provider/pose_detector_provider.dart';

class CameraSection extends StatefulWidget {
  const CameraSection({super.key});

  @override
  _CameraSectionState createState() => _CameraSectionState();
}

class _CameraSectionState extends State<CameraSection> {
  
  @override
  void initState() {
    super.initState();
   
  }

  @override
  Widget build(BuildContext context) {

    PoseDetectorProvider workoutProvider = Provider.of<PoseDetectorProvider>(context,listen: true);
    CameraController controller = workoutProvider.controller!;

    if (!controller.value.isInitialized) {
      return Center(child: CircularProgressIndicator());
    }

    return Scaffold(
    
      body: CameraPreview(controller),
    );
  }}
